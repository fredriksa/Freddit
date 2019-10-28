<?php
/**
 * UserRegistrationStatus contains status codes 
 * for the registration process.
 */
class UserRegistrationStatus {
  const SUCCESS = 0;
  const FAILURE_INVALID_USERNAME = 1;
  const FAILURE_INVALID_PASSWORD = 2;
  const FAILURE_USERNAME_OCCUPIED = 3;
  const FAILURE_INVALID_EMAIL = 4;
}
?>