<?php
session_start();
$CURRENT_PAGE = "Messages";
include("includes/header.php");
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;

if (!$receiver_id) {
    // Fetch list of users that the current user follows
    $sql = "SELECT users.id, users.name 
            FROM followers 
            INNER JOIN users ON followers.user_id = users.id 
            WHERE followers.follower_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt->close();
}
?>

<h1>Messages</h1>

<?php if (!$receiver_id): ?>
    <p>Select a user to message:</p>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><a href="messages.php?receiver_id=<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></a></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?php
    // Verify if the receiver_id is a user the sender follows
    $sql = "SELECT * FROM followers WHERE user_id = ? AND follower_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $receiver_id, $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("Error: You can only message users you follow.");
    }

    // Fetch messages between the sender and receiver
    $sql = "SELECT messages.*, sender.name AS sender_name, receiver.name AS receiver_name
            FROM messages
            INNER JOIN users AS sender ON messages.sender_id = sender.id
            INNER JOIN users AS receiver ON messages.receiver_id = receiver.id
            WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
            ORDER BY created_at ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    $stmt->close();
    ?>

    <div id="messages">
        <?php foreach ($messages as $message): ?>
            <p><strong><?php echo ($message['sender_id'] == $sender_id) ? 'You' : htmlspecialchars($message['sender_name']); ?>:</strong> <?php echo htmlspecialchars($message['message']); ?></p>
        <?php endforeach; ?>
    </div>

    <form id="messageForm">
        <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
        <textarea name="message" required></textarea>
        <button type="submit">Send</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        var receiver_id = "<?php echo $receiver_id; ?>";

        $("#messageForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "send_message.php",
                type: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == "success") {
                        $("textarea[name='message']").val("");
                        fetchMessages();
                    } else {
                        alert("Failed to send message.");
                    }
                }
            });
        });

        function fetchMessages() {
            $.ajax({
                url: "fetch_messages.php",
                type: "GET",
                data: { receiver_id: receiver_id },
                success: function(data) {
                    var messages = JSON.parse(data);
                    var messagesHtml = "";
                    for (var i = 0; i < messages.length; i++) {
                        messagesHtml += "<p><strong>" + (messages[i].sender_id == <?php echo $sender_id; ?> ? "You" : messages[i].sender_name) + ":</strong> " + messages[i].message + "</p>";
                    }
                    $("#messages").html(messagesHtml);
                }
            });
        }

        fetchMessages();
        setInterval(fetchMessages, 5000); // Fetch new messages every 5 seconds
    });
    </script>
<?php endif; ?>

<?php include("includes/footer.php"); ?>