<?php
include("includes/header.php");
?>

<h1>Contact Us</h1>
<p>If you have any questions, suggestions, or feedback, please feel free to contact us using the form below:</p>

<form method="post" action="contact_process.php">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="message">Message:</label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>

<?php include("includes/footer.php"); ?>
