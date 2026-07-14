<?php
$page_title = "Admission Application";
include 'includes/header.php'; // Use the new header

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- DATA COLLECTION ---
    $student_name = $_POST['student_name'] ?? '';
    $student_dob = $_POST['student_dob'] ?? '';
    $student_gender = $_POST['student_gender'] ?? '';
    $grade_applying = $_POST['grade_applying'] ?? '';
    $previous_school = $_POST['previous_school'] ?? '';
    $guardian_name = $_POST['guardian_name'] ?? '';
    $guardian_relationship = $_POST['guardian_relationship'] ?? '';
    $guardian_phone = $_POST['guardian_phone'] ?? '';
    $guardian_email = $_POST['guardian_email'] ?? '';
    $address = $_POST['address'] ?? '';
    $notes = $_POST['notes'] ?? '';

    // --- FILE UPLOAD HANDLING ---
    $profile_pic_path = '';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $upload_dir = 'uploads/admission_photos/';
        // Create a unique filename to prevent overwriting
        $filename = time() . '_' . basename($_FILES["profile_pic"]["name"]);
        $target_file = $upload_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Basic validation
        $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
        if ($check === false) {
            $error = "File is not an image.";
        } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        if (empty($error)) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                $profile_pic_path = $target_file;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $error = "Profile picture is required.";
    }

    // --- DATABASE INSERTION ---
    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO admissions (student_name, student_dob, student_gender, grade_applying, previous_school, guardian_name, guardian_relationship, guardian_phone, guardian_email, address, profile_pic_path, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // s = string, d = double, i = integer, b = blob
        $stmt->bind_param("ssssssssssss", $student_name, $student_dob, $student_gender, $grade_applying, $previous_school, $guardian_name, $guardian_relationship, $guardian_phone, $guardian_email, $address, $profile_pic_path, $notes);

        if ($stmt->execute()) {
            $message = "Your application has been submitted successfully! We will review it and be in touch shortly.";
        } else {
            $error = "There was an error submitting your application. Please try again. " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!-- The <main> tag is opened in header.php -->
<div class="bg-white p-8 rounded-lg shadow-md">
    <?php if (!empty($message)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 text-center rounded-lg mb-6">
            <h3 class="text-2xl font-bold">Thank You!</h3>
            <p><?= $message ?></p>
            <a href="/" class="inline-block mt-4 bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700">Back to Home</a>
        </div>
    <?php else: ?>
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?= $error ?></span>
            </div>
        <?php endif; ?>

        <!-- The 'enctype' is crucial for file uploads -->
        <form action="admission.php" method="POST" enctype="multipart/form-data" class="space-y-8">
            <!-- Student Information -->
            <fieldset class="border p-6 rounded-lg">
                <legend class="text-xl font-semibold px-2">Student Information</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="student_name" class="block font-medium">Full Name</label>
                        <input type="text" name="student_name" class="w-full mt-1 p-2 border rounded-md" required>
                    </div>
                    <div>
                        <label for="student_dob" class="block font-medium">Date of Birth</label>
                        <input type="date" name="student_dob" class="w-full mt-1 p-2 border rounded-md" required>
                    </div>
                    <div>
                        <label for="student_gender" class="block font-medium">Gender</label>
                        <select name="student_gender" class="w-full mt-1 p-2 border rounded-md" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                     <div>
                        <label for="profile_pic" class="block font-medium">Profile Picture</label>
                        <input type="file" name="profile_pic" accept="image/*" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 hover:file:bg-blue-100" required>
                    </div>
                </div>
            </fieldset>

            <!-- Academic Information -->
            <fieldset class="border p-6 rounded-lg">
                <legend class="text-xl font-semibold px-2">Academic Information</legend>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="grade_applying" class="block font-medium">Grade Applying For</label>
                         <select name="grade_applying" class="w-full mt-1 p-2 border rounded-md" required>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="Grade <?= $i ?>">Grade <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div>
                        <label for="previous_school" class="block font-medium">Previous School (if any)</label>
                        <input type="text" name="previous_school" class="w-full mt-1 p-2 border rounded-md">
                    </div>
                </div>
            </fieldset>

            <!-- Guardian Information -->
            <fieldset class="border p-6 rounded-lg">
                <legend class="text-xl font-semibold px-2">Parent / Guardian Information</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="guardian_name" class="block font-medium">Full Name</label>
                        <input type="text" name="guardian_name" class="w-full mt-1 p-2 border rounded-md" required>
                    </div>
                    <div>
                        <label for="guardian_relationship" class="block font-medium">Relationship to Student</label>
                        <input type="text" name="guardian_relationship" placeholder="e.g., Father, Mother, Guardian" class="w-full mt-1 p-2 border rounded-md" required>
                    </div>
                     <div>
                        <label for="guardian_phone" class="block font-medium">Contact Phone</label>
                        <input type="tel" name="guardian_phone" class="w-full mt-1 p-2 border rounded-md" required>
                    </div>
                    <div>
                        <label for="guardian_email" class="block font-medium">Contact Email</label>
                        <input type="email" name="guardian_email" class="w-full mt-1 p-2 border rounded-md" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block font-medium">Home Address</label>
                        <textarea name="address" rows="3" class="w-full mt-1 p-2 border rounded-md" required></textarea>
                    </div>
                </div>
            </fieldset>
            
            <button type="submit" class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-semibold text-lg hover:bg-green-700 transition-colors">Submit Application</button>
        </form>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php'; // Use the new footer
?>