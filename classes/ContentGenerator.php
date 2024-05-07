<?php
class ContentGenerator {
    public function getUserContent($role) {
        switch ($role) {
            case 'admin':
                return "
                    <p><span>Welcome, Admin! </span><strong>Here are your admin tools and analytics.</strong></p>
                    <ul>
                        <li><a href='admin_user_management.php'>User Management</a></li>
                        <li><a href='admin_locations.php'>Location Management</a></li>
                        <li><a href='admin_add_hub_manager.php'>Hub Manager</a></li>
                        <li><a href='TaskTypeManager.php'>Task Management</a></li>
                    </ul>
                ";
            case 'manager':
                return "
                <p><span>Welcome, Manager! </span><strong>Here is your management dashboard.</strong></p>
                <ul>
                    <li><a href='create_user.php'>Create User</a></li>
                    <li><a href='task_can_do.php'>What can users do?</a></li>
                    <li><a href='profile.php'>Profile</a></li>
                    <li><a href='all_users.php'>View all Users</a></li>
                    <li><a href='maneger_time_off.php'>Time off Request</a></li>
            </ul>
            ";
            case 'user':
                return "<p>Welcome, User! Enjoy your visit.</p>
                    <li><a href='profile.php'>Profile</a></li>
                    <li><a href='user_time_off.php'>Time off</a></li>";
            default:
                return "<p>Welcome! Contact support to assign your role.</p>";
        }
    }
}
