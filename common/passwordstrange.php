<?php
class PasswordStrange
{
    public $password;
    
    function __construct($password)
    {
        $this->password = $password;
    }
    
    public function isStrange() {
        $width = 0;
        
        if (strlen($this->password) >= 8) {
            $width += 25;
       }

        if (preg_match('/[A-Z]/', $this->password)) {
            $width += 25;
       }

        if (preg_match('/[a-z]/', $this->password)) {
            $width += 25;
       }
        
        if (preg_match('/[0-9]/', $this->password)) {
            $width += 25;
      }
        
        return $width;
    }
    public function getWeakPoints() {
        $issues = [];
        
        if (strlen($this->password) < 8) {
            $issues[] = 'Password must be at least 8 characters long';
        }
        
        if (!preg_match('/[A-Z]/', $this->password)) {
            $issues[] = 'Password must contain at least one uppercase letter';
        }
        
        if (!preg_match('/[a-z]/', $this->password)) {
            $issues[] = 'Password must contain at least one lowercase letter';
        }
        
        if (!preg_match('/[0-9]/', $this->password)) {
            $issues[] = 'Password must contain at least one number';
        }
        
        return $issues;
    }
    
    public function getStrengthLevel() {
        $strength = $this->isStrange();
        
        if ($strength >= 100) {
            return 'Very Strong';
        } elseif ($strength >= 75) {
            return 'Strong';
        } elseif ($strength >= 50) {
            return 'Medium';
        } elseif ($strength >= 25) {
            return 'Weak';
        } else {
            return 'Very Weak';
        }
    }
    
    public function isValid() {
        return $this->isStrange() >= 50;
    }
}
?>