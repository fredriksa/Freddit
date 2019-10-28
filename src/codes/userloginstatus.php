<?php
/**
 * UserLoginStatus contains status codes 
 * for the login process.
 */
class UserLoginStatus {
  const SUCCESS = 0;
  const FAILURE_INVALID_USERNAME = 1;
  const FAILURE_INVALID_PASSWORD = 2;
  const FAILURE_INVALID_COMBINATION = 3;
}
?>