<?php
require_once __DIR__.'/../../controllers/ReportController.php';
require_once __DIR__.'/../../controllers/CafeController.php';
require_once __DIR__.'/../../controllers/TagController.php';

$reportController = new ReportController;
$reportController->handleReportConfirmation();
$reports = $reportController->getPendingReports();

$cafeController = new CafeController;
$pendingCafes = $cafeController->fetchPendingCafes(); // Giả sử bạn thêm phương thức này
?>
<main>
    <section class="text-center py-3">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, Admin.</p>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCafeModal">Add New Café</button>
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </section>
    <section class="container mb-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pending Cafés</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="sticky-top bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>District</th>
                                <th>Price Range</th>
                                <th>Tags</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pendingCafes)) { ?>
                            <tr>
                                <td colspan="7" class="text-center py-3">No pending cafes found.</td>
                            </tr>
                            <?php } else {
                                $tagController = new TagController;
                                foreach ($pendingCafes as $cafe) {
                                    $tags = $tagController->getTagsByCafeId($cafe['id']);
                                    ?>
                            <tr>
                                <td><?= htmlspecialchars($cafe['id']) ?></td>
                                <td><?= htmlspecialchars($cafe['name']) ?></td>
                                <td><?= htmlspecialchars($cafe['address']) ?></td>
                                <td><?= htmlspecialchars($cafe['district_name']) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match ($cafe['price_range']) {
                                        'Low' => 'bg-success',
                                        'Medium' => 'bg-warning text-dark',
                                        default => 'bg-danger',
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $cafe['price_range'] ?></span>
                                </td>
                                <td>
                                    <?php foreach (array_column($tags, 'name') as $tag) { ?>
                                    <span class="badge bg-info text-dark"><?= htmlspecialchars($tag) ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success approve-cafe" data-id="<?= $cafe['id'] ?>">
                                        <i class="bi bi-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-cafe" data-id="<?= $cafe['id'] ?>"
                                        data-name="<?= htmlspecialchars($cafe['name']) ?>">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php }
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <section class="container mb-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Café List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="sticky-top bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>District</th>
                                <th>Price Range</th>
                                <th>Tags</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $cafes = $cafeController->fetchCafesForAdmin();
if (empty($cafes)) { ?>
                            <tr>
                                <td colspan="7" class="text-center py-3">No cafes found.</td>
                            </tr>
                            <?php } else {
                                $tagController = new TagController;
                                foreach ($cafes as $cafe) {
                                    $tags = $tagController->getTagsByCafeId($cafe['id']);
                                    ?>
                            <tr>
                                <td><?= htmlspecialchars($cafe['id']) ?></td>
                                <td><?= htmlspecialchars($cafe['name']) ?></td>
                                <td><?= htmlspecialchars($cafe['address']) ?></td>
                                <td><?= htmlspecialchars($cafe['district_name']) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match ($cafe['price_range']) {
                                        'Low' => 'bg-success',
                                        'Medium' => 'bg-warning text-dark',
                                        default => 'bg-danger',
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $cafe['price_range'] ?></span>
                                </td>
                                <td>
                                    <?php foreach (array_column($tags, 'name') as $tag) { ?>
                                    <span class="badge bg-info text-dark"><?= htmlspecialchars($tag) ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-cafe" data-id="<?= $cafe['id'] ?>"
                                        data-bs-toggle="modal" data-bs-target="#editCafeModal">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-cafe" data-id="<?= $cafe['id'] ?>"
                                        data-name="<?= htmlspecialchars($cafe['name']) ?>">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php }
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="addCafeModal" tabindex="-1" aria-labelledby="addCafeModalLabel" aria-hidden="true">
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
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?= $inputs['name'] ?? '' ?>">
                            <small class="text-danger error-message" data-field="name"></small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description"
                                name="description"><?= $inputs['description'] ?? '' ?></textarea>
                            <small class="text-danger error-message" data-field="description"></small>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="<?= $inputs['address'] ?? '' ?>">
                            <small class="text-danger error-message" data-field="address"></small>
                        </div>

                        <div class="mb-3">
                            <label for="price_range" class="form-label">Price Range</label>
                            <select class="form-control" id="price_range" name="price_range">
                                <option value="">Select price range</option>
                                <option value="Low"
                                    <?= (isset($inputs['price_range']) && $inputs['price_range'] == 'Low') ? 'selected' : '' ?>>
                                    Low</option>
                                <option value="Medium"
                                    <?= (isset($inputs['price_range']) && $inputs['price_range'] == 'Medium') ? 'selected' : '' ?>>Medium</option>
                                <option value="High"
                                    <?= (isset($inputs['price_range']) && $inputs['price_range'] == 'High') ? 'selected' : '' ?>>
                                    High</option>
                            </select>
                            <small class="text-danger error-message" data-field="price_range"></small>
                        </div>

                        <div class="mb-3">
                            <label for="district_id" class="form-label">District</label>
                            <select class="form-control" id="district_id" name="district_id">
                                <option value="">Select a district</option>
                                <?php foreach ($districts as $district) { ?>
                                <option value="<?= $district['id'] ?>"
                                    <?= (isset($inputs['district_id']) && $inputs['district_id'] == $district['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($district['name']) ?>
                                </option>
                                <?php } ?>
                            </select>
                            <small class="text-danger error-message" data-field="district_id"></small>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags (comma-separated)</label>
                            <input type="text" class="form-control" id="tags" name="tags"
                                value="<?= $inputs['tags'] ?? '' ?>">
                            <small class="text-danger error-message" data-field="tags"></small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Café</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editCafeModal" tabindex="-1" aria-labelledby="editCafeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editCafeModalLabel">Edit Café</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCafeForm" method="post">
                        <input type="hidden" id="edit_cafe_id" name="cafe_id">

                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Café Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="edit_address" name="address" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_price_range" class="form-label">Price Range</label>
                            <select class="form-control" id="edit_price_range" name="price_range" required>
                                <option value="">Select price range</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_district_id" class="form-label">District</label>
                            <select class="form-control" id="edit_district_id" name="district_id" required>
                                <option value="">Select a district</option>
                                <?php foreach ($districts as $district) { ?>
                                <option value="<?= $district['id'] ?>"><?= htmlspecialchars($district['name']) ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_tags" class="form-label">Tags (comma-separated)</label>
                            <input type="text" class="form-control" id="edit_tags" name="tags">
                            <small class="form-text text-muted">Enter tags separated by commas (e.g., wifi, quiet,
                                coffee)</small>
                        </div>

                        <div id="editFormErrors" class="alert alert-danger" style="display: none;"></div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="saveEditBtn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container mb-8">
       <section class="container mb-5"> 
        <div class ="card">
        <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pending reports</h5>
            </div>
        <?php if (empty($reports)) { ?>
            <p class="ms-3 mt-2">No pending reports.</p>
        <?php } else { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cafe Name</th>
                        <th>Type</th>
                        <th>Current Value</th>
                        <th>Proposed Value</th>
                        <th>Timestamp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report) { ?>
                        <tr>
                            <td><?= htmlspecialchars($report['id']) ?></td>
                            <td><?= htmlspecialchars($report['cafe_name']) ?></td>
                            <td><?= htmlspecialchars($report['report_type']) ?></td>
                            <td><?= htmlspecialchars($report['current_value']) ?></td>
                            <td><?= htmlspecialchars($report['proposed_value']) ?></td>
                            <td><?= htmlspecialchars($report['timestamp']) ?></td>
                            <td>
                                <form class="report-action-form d-inline" data-report-id="<?= $report['id'] ?>" data-action="confirm">
                                    <button type="submit" class="btn btn-success btn-sm">Confirm</button>
                                </form>
                                <form class="report-action-form d-inline" data-report-id="<?= $report['id'] ?>" data-action="delete">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
            </div>
        </section>
    </div>
</main>

<script src="/Cafes-Viewing-Website/public/js/admin.js"></script>
