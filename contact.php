<?php
$page_title = "Contact Us";
include 'includes/header.php'; // Includes DB connection, settings, and header HTML

$message = '';
$error = '';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Data Collection and Sanitization ---
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message_body = trim($_POST['message'] ?? '');

    // --- Validation ---
    if (empty($name) || empty($email) || empty($subject) || empty($message_body)) {
        $error = "All fields are required. Please fill out the entire form.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "The email address you entered is not valid.";
    }

    // --- Process if No Errors ---
    if (empty($error)) {
        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, subject, message) VALUES (?, ?, ?, ?)");
        // 'ssss' means we are binding 4 string parameters
        $stmt->bind_param("ssss", $name, $email, $subject, $message_body);
        
        if ($stmt->execute()) {
            $message = "Thank you for contacting us, " . htmlspecialchars($name) . "! Your message has been sent successfully. We will get back to you shortly.";
        } else {
            $error = "Sorry, there was an error sending your message. Please try again later.";
        }
        $stmt->close();
    }
}
?>

<!-- The <main> tag is opened in header.php -->

<div class="bg-white p-8 rounded-lg shadow-md">
    
    <?php if (!empty($message)): ?>
        <!-- Success Message Display -->
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 text-center rounded-lg">
            <h3 class="text-2xl font-bold">Message Sent!</h3>
            <p><?= $message ?></p>
            <a href="/" class="inline-block mt-4 bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700">Return to Homepage</a>
        </div>
    <?php else: ?>
        <!-- Contact Form and Info Display -->

        <?php if (!empty($error)): ?>
            <!-- Error Message Display -->
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline"><?= $error ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- Left Column: Contact Information and Map -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Get in Touch</h2>
                    <p class="text-gray-600">
                        We'd love to hear from you! Whether you have a question about admissions, programs, or anything else, our team is ready to answer all your questions.
                    </p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="ri-map-pin-2-fill text-blue-600 text-2xl mr-4 mt-1"></i>
                        <div>
                            <h4 class="font-semibold">Our Address</h4>
                            <p class="text-gray-600"><?= htmlspecialchars($settings['contact_address'] ?? '123 Education Street, Springfield, ST 12345') ?></p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="ri-mail-send-fill text-blue-600 text-2xl mr-4 mt-1"></i>
                        <div>
                            <h4 class="font-semibold">Email Us</h4>
                            <a href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>" class="text-gray-600 hover:text-blue-600 hover:underline"><?= htmlspecialchars($settings['contact_email'] ?? 'info@school.com') ?></a>
                        </div>
                    </div>
                     <div class="flex items-start">
                        <i class="ri-phone-fill text-blue-600 text-2xl mr-4 mt-1"></i>
                        <div>
                            <h4 class="font-semibold">Call Us</h4>
                            <p class="text-gray-600"><?= htmlspecialchars($settings['contact_phone'] ?? '(555) 123-4567') ?></p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Our Location</h3>
                    <!-- Google Maps Embed -->
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden border">
                        <!-- IMPORTANT: Replace this src with your school's Google Maps embed code -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.225826966874!2d144.9613123153177!3d-37.81720997975195!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0x5045675218ce7e0!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1617253549802!5m2!1sen!2sau" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>

            <!-- Right Column: Contact Form -->
            <div>
                 <form action="contact.php" method="POST" class="space-y-6 bg-gray-50 p-8 rounded-lg">
                    <div>
                        <label for="name" class="block font-medium text-gray-700">Your Name</label>
                        <input type="text" name="name" id="name" class="w-full mt-1 p-3 border rounded-md" required>
                    </div>
                     <div>
                        <label for="email" class="block font-medium text-gray-700">Your Email</label>
                        <input type="email" name="email" id="email" class="w-full mt-1 p-3 border rounded-md" required>
                    </div>
                     <div>
                        <label for="subject" class="block font-medium text-gray-700">Subject</label>
                        <input type="text" name="subject" id="subject" class="w-full mt-1 p-3 border rounded-md" required>
                    </div>
                    <div>
                        <label for="message" class="block font-medium text-gray-700">Message</label>
                        <textarea name="message" id="message" rows="6" class="w-full mt-1 p-3 border rounded-md" required></textarea>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

        </div>
    <?php endif; ?>
</div>

<?php
// The footer file closes the <main> tag and the rest of the document.
include 'includes/footer.php'; 
?>