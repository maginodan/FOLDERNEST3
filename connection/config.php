<?php

$conn = mysqli_connect("localhost", "root", "", "dms_db");

if (!$conn) {
    echo "Connection Failed";
}