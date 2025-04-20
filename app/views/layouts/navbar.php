<nav class="sidebar" id="sidebar">
    <ul class="ul-sidebar">
        <li class="li-sidebar">
            <span class="greeting">Hello <?= current_user() ?></span>
        </li>
        <li class="li-sidebar"><a href="index.php?page=home">Home</a></li>
        <li class="li-sidebar"><a href="?page=about">About</a></li>
        <li class="li-sidebar text-center">
            <button class="btn border-white p-0" data-bs-toggle="modal" data-bs-target="#addCafeModal" title="Add New Café" style="height:30px; width:30px;<?php if (current_user() === 'guest') {
                echo 'display: none';
            } ?>">
                <i class="bi bi-plus-lg plus-icon"></i>
            </button>
        </li>
        <li class="li-sidebar"><a href="?page=saves">Saves</a></li>
        <li class="li-sidebar"><a href="?page=contact">Contact</a></li>
        <div class="dropdown icon-drop">
            <button class="btn p-0 border-0 dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= BASE_URL ?>/images/assets/icon_login.jpg" class="rounded-circle" alt="Login Icon" id="login-icon" width="40" height="40">
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <?php if (current_user() === 'guest') { ?>
            <li>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <img src="<?= BASE_URL ?>/images/assets/icon_login.jpg" class="rounded-circle me-2" width="30" height="30">
                    <span><strong><?= current_user() ?></strong></span>
                </a>
            </li>
            <li><a class="dropdown-item dropdown-item-login text-primary" href="login.php">Login
                </a></li>
                <?php } elseif (current_user() === 'admin') {?>
                <li><a class="dropdown-item d-flex align-items-center" href="#">
                        <img src="<?= BASE_URL ?>/images/assets/icon_login.jpg" class="rounded-circle me-2" width="30" height="30">
                        <span><strong><?= current_user() ?></strong></span>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="admin.php">Dashboard</a></li>
                <li><a class="dropdown-item disabled" href="#">Help & Support</a></li>
                <li><a class="dropdown-item disabled" href="#">Display & Accessibility</a></li>
                <li><a class="dropdown-item disabled" href="#">Give Feedback</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item dropdown-item-logout text-danger" href="logout.php">Log Out</a></li>
                <?php } else { ?>
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <img src="<?= BASE_URL ?>/images/assets/icon_login.jpg" class="rounded-circle me-2" width="30" height="30">
                        <span><strong><?= current_user() ?></strong></span>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item disabled" href="#">Settings & Privacy</a></li>
                <li><a class="dropdown-item disabled" href="#">Help & Support</a></li>
                <li><a class="dropdown-item disabled" href="#">Display & Accessibility</a></li>
                <li><a class="dropdown-item disabled" href="#">Give Feedback</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item dropdown-item-logout text-danger" href="logout.php">Log Out</a></li>
                <?php } ?>
            </ul>
        </div>
    
    </ul>
    <div class="mb-4 text-center link-items">
        <a href="logout.php" class="w-100 border border-warning logout-link p-2">Logout</a>
        </div>
</nav>
<div class="modal fade text-start text-black" id="addCafeModal" tabindex="-1" aria-labelledby="addCafeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addCafeModalLabel">Add New Café</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="formErrors" class="alert alert-danger" style="display: none;"></div>
                <form id="addCafeForm" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Café Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small class="text-danger error-message" data-field="name"></small>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                        <small class="text-danger error-message" data-field="description"></small>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                        <small class="text-danger error-message" data-field="address"></small>
                    </div>
                    <div class="mb-3">
                        <label for="price_range" class="form-label">Price Range</label>
                        <select class="form-control" id="price_range" name="price_range" required>
                            <option value="">Select price range</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                        <small class="text-danger error-message" data-field="price_range"></small>
                    </div>
                    <div class="mb-3">
                        <label for="district_id" class="form-label">District</label>
                        <select class="form-control" id="district_id" name="district_id" required>
                            <option selected disabled>Districts</option>
                            <option value="1">District 1</option>
                            <option value="2">District 2</option>
                            <option value="3">District 3</option>
                            <option value="4">District 4</option>
                            <option value="5">District 5</option>
                            <option value="6">District 6</option>
                            <option value="7">District 7</option>
                            <option value="8">District 8</option>
                            <option value="9">District 9</option>
                            <option value="10">District 10</option>
                            <option value="11">District 11</option>
                            <option value="12">District 12</option>
                            <option value="13">Binh Thanh District</option>
                            <option value="14">Go Vap District</option>
                            <option value="15">Phu Nhuan District</option>
                            <option value="16">Tan Binh District</option>
                            <option value="17">Tan Phu District</option>                        </select>
                            <small class="text-danger error-message" data-field="district_id"></small>
                    </div>
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags (comma-separated)</label>
                        <input type="text" class="form-control" id="tags" name="tags">
                        <small class="text-danger error-message" data-field="tags"></small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveAddBtn">Add Café</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= BASE_URL ?>/js/navbar_add.js"></script>
