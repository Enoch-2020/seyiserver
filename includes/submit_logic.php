<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inquiry'])) {
    
    // 1. Collect and Sanitize Data
    $full_name = htmlspecialchars(strip_tags($_POST['full_name']));
    $budget = htmlspecialchars(strip_tags($_POST['budget']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(strip_tags($_POST['phone']));
    $purpose = htmlspecialchars(strip_tags($_POST['project_purpose']));
    $description = htmlspecialchars(strip_tags($_POST['project_description']));

    // 2. Prepare SQL Statement
    $sql = "INSERT INTO enquiries_form (full_name, budget, email, phone, project_purpose, project_description) 
            VALUES (:name, :budget, :email, :phone, :purpose, :description)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':name'        => $full_name,
            ':budget'      => $budget,
            ':email'       => $email,
            ':phone'       => $phone,
            ':purpose'     => $purpose,
            ':description' => $description
        ]);

        if ($result) {
            // Success: Redirect back with a success message
            header("Location: ../index.php?status=success#contact");
            exit();
        }
    } catch (PDOException $e) {
        // Error: Redirect back with error message
        header("Location: ../index.php?status=error#contact");
        exit();
    }
} else {
    // If someone tries to access this file directly, kick them back to index
    header("Location: ../index.php");
    exit();
}