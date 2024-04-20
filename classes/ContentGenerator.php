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
                    </ul>
                ";
            case 'manager':
                return "<p>Welcome, Manager! Here is your management dashboard.</p>";
            case 'user':
                return "<p>Welcome, User! Enjoy your visit.</p>";
            default:
                return "<p>Welcome! Contact support to assign your role.</p>";
        }
    }
}
