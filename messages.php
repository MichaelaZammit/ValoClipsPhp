<?php
session_start();
$CURRENT_PAGE = "Messages";
include("includes/header.php");

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if user is not logged in
    exit();
}

// Debugging: Output session data and URL parameters
echo "<pre>Session Data: ";
print_r($_SESSION);
echo "</pre>";

echo "<pre>URL Parameters: ";
print_r($_GET);
echo "</pre>";

if(isset($_GET['receiver_id'])) {
    $receiver_id = $_GET['receiver_id']; // ID of the user to message
} else {
    // Handle the case where receiver_id is not set
    die("Error: receiver_id not set.");
}
?>

<h1>Messages</h1>
<p>View and send messages to other users.</p>

<div id="messages"></div>

<form id="messageForm">
    <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
    <textarea name="message" required></textarea>
    <button type="submit">Send</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var receiver_id = "<?php echo $receiver_id; ?>";

    function fetchMessages() {
        $.ajax({
            url: "fetch_messages.php",
            type: "GET",
            data: { receiver_id: receiver_id },
            success: function(data) {
                var messages = JSON.parse(data);
                var messagesHtml = "";
                for (var i = 0; i < messages.length; i++) {
                    messagesHtml += "<p><strong>" + (messages[i].sender_id == <?php echo $_SESSION['user_id']; ?> ? "You" : "User " + messages[i].sender_id) + ":</strong> " + messages[i].message + "</p>";
                }
                $("#messages").html(messagesHtml);
            }
        });
    }

    fetchMessages();

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

    setInterval(fetchMessages, 5000); // Fetch new messages every 5 seconds
});
</script>

<?php include("includes/footer.php"); ?>
