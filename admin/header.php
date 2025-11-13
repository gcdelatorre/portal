<?php
if (!isset($_SESSION)) { session_start(); }

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../portal/login.php");
    exit();
}
?>
<link rel="stylesheet" href="../styles.css">

<div class="sidebar" id="sidebar">
    <h1>Admin</h1>
    <nav class="nav">
        <a href="accounts.php"><span class="label">Accounts</span></a>
        <a href="applications.php"><span class="label">Applications</span></a>
        <a href="renewals.php"><span class="label">Renewals</span></a>
        <a href="scholars.php"><span class="label">Scholars</span></a>
    </nav>
    <div class="logout">
        <form method="post">
            <button class="btn" type="submit" name="logout">Logout</button>
        </form>
    </div>
</div>

<button class="sidebar-toggle" id="sidebarToggle" title="Toggle sidebar">←</button>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var btn = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');
    if (!btn || !sidebar) return;
    btn.addEventListener('click', function(e){
        e.preventDefault();
        sidebar.classList.toggle('collapsed');
        btn.textContent = sidebar.classList.contains('collapsed') ? '→' : '←';
    });
});
</script>
