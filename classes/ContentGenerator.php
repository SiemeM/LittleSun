<?php
class ContentGenerator {
    public function getUserContent($role) {
        switch ($role) {
            case 'admin':
                return "
                    <p>Welcome, Admin! Here are your admin tools and analytics.</p>
                    <ul>
                        <li><a href='admin_user_management.php'>User management</a></li>
                        <li><a href='admin_locations.php'>Location management</a></li>
                        <li><a href='admin_add_hub_manager.php'>Hub manager</a></li>
                        <li><a href='TaskTypeManager.php'>Task Manegment</a></li>
                    </ul>
                ";
            case 'manager':
                return "
                <p>Welcome, Manager! Here is your management dashboard.</p>
                <ul>
                    <li><a href='create_user.php'>Create User</a></li>
                    <li><a href='task_can_do.php'>What can users do?</a></li>
                    <li><a href='profile.php'>Profile</a></li>
                    <li><a href='all_users.php'>View all Users</a></li>
            </ul>
            ";
            case 'user':
                return "<p>Welcome, User! Enjoy your visit.</p>
                <li><a href='profile.php'Profile</a></li>
                <li><a href='all_users.php'>View all Users</a></li>"
                ;
            default:
                return "<p>Welcome! Contact support to assign your role.</p>";
        }
    }
}
