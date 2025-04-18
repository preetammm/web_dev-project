<?php
// Create admin/includes directory if it doesn't exist
if (!is_dir('admin/includes')) {
    mkdir('admin/includes', 0777, true);
    echo "Created admin/includes directory";
} else {
    echo "admin/includes directory already exists";
}
?>
