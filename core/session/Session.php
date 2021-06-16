<?php


namespace application\core\session;


class Session {
    protected const FLASH_KEY = 'flash_message';

    public function __construct() {
        session_start();
        $this->generateCsrfToken();

        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    private function generateCsrfToken() {
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
    }

    public function set($key, $value, $serialize = false) {
        $value = $serialize ? serialize($value) : $value;
        $_SESSION[$key] = $value;
    }

    public function get($key, $unserialize = false) {
        $value = $_SESSION[$key] ?? null;
        return $unserialize && $value ? unserialize($value) : $value;
    }

    public function remove($key) {
        unset($_SESSION[$key]);
    }

    public function destroy() {
        session_destroy();
    }

    public function setFlash($key, $message) {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key) {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function __destruct() {
        $this->removeFlashMessages();
    }

    private function removeFlashMessages() {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}