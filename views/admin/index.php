<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: " . BASE_URL . "/home");
    exit;
}

$tab = $_GET['tab'] ?? 'users';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - SINERGI</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100">
<?php require_once 'views/partials/navbar.php'; ?>

<main class="container mx-auto px-6 pt-30 pb-6 flex gap-6">
  <!-- SIDEBAR -->
  <aside class="w-64">
    <?php require 'views/admin/sidebar.php'; ?>
  </aside>

  <!-- CONTENT -->
  <section class="flex-1">
    <?php
      switch ($tab) {
        case 'stats':
          require 'views/admin/stats.php';
          break;
        case 'reports':
          require 'views/admin/reports.php';
          break;
        case 'users':
        default:
          require 'views/admin/users.php';
          break;
      }
    ?>
  </section>

</main>
</body>
</html>
