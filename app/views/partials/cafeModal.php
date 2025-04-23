<div class="modal fade text-start" id="cafeModal<?= strval($cafe['id']) ?>" tabindex="-1"
    aria-labelledby="cafeModalLabel<?= strval($cafe['id']) ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><?= htmlspecialchars($cafe['name']) ?></h2>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-outline-primary me-2 pin-btn" 
                        data-cafe-id="<?= strval($cafe['id']) ?>" 
                        onclick="togglePin(<?= strval($cafe['id']) ?>)" style="<?php if (current_user() === 'guest') {
                            $style = 'display:none;';
                            echo $style;
                        }?>">
                        <i class="bi bi-pin-angle"></i> Save
                    </button>
                    <button type="button" class="btn check btn-outline-danger me-2 report-btn" data-bs-toggle="modal"
                        data-bs-target="#reportModal<?= strval($cafe['id']) ?>"
                        data-cafe-id="<?= strval($cafe['id']) ?>"
                        data-cafe-name="<?= htmlspecialchars($cafe['name']) ?>"
                        data-cafe-address="<?= htmlspecialchars($cafe['address'].', '.$cafe['district_name_en']) ?>"
                        data-cafe-price="<?= htmlspecialchars($cafe['price_range']) ?>" style="<?php if (current_user() === 'guest') {
                            $style = 'display:none;';
                            echo $style;
                        }?>">
                        Report
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="carousel<?= strval($cafe['id']) ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                if (! empty($cafe['images'])) {
                                    foreach ($cafe['images'] as $key => $imgPath) {
                                        ?>
                                        <div class="carousel-item <?= $key === 0 ? 'active' : '' ?>">
                                            <img src="<?= htmlspecialchars($imgPath) ?>" class="d-block w-100 img-fluid"
                                                alt="Carousel image">
                                        </div>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="carousel-item active">
                                        <img src="default.png" class="d-block w-100" alt="Default image">
                                    </div>
                                    <?php
                                }
?>
                            </div>
                            <?php if (count($cafe['images']) > 1) { ?>
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carousel<?= strval($cafe['id']) ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carousel<?= strval($cafe['id']) ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex flex-column justify-content-center">
                        <p><strong>Description:</strong> <?= htmlspecialchars($cafe['description']) ?></p>
                        <p><strong>Tags:</strong>
                            <?php if (! empty($cafe['tags'])) { ?>
                                <?php foreach ($cafe['tags'] as $tag) { ?>
                                    <span class="badge bg-primary"><?= htmlspecialchars($tag['name']) ?></span>
                                <?php } ?>
                            <?php } else { ?>
                                <span class="text-muted">No tags available</span>
                            <?php } ?>
                        </p>
                        <p><strong>Price Range:</strong> <?= htmlspecialchars($cafe['price_range']) ?></p>
                        <p><strong>Location:</strong>
                            <?= htmlspecialchars($cafe['address'].', '.$cafe['district_name_en']) ?></p>
                        <?php
                        $fullAddress = $cafe['address'].', '.$cafe['district_name_vn'].', Hồ Chí Minh';
$encodedAddress = urlencode($fullAddress);
$mapSrc = "https://www.google.com/maps?q={$encodedAddress}&output=embed";
?>
                        <div class="ratio h-100">
                            <iframe src="<?= $mapSrc ?>" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reportModal<?= strval($cafe['id']) ?>" tabindex="-1"
    aria-labelledby="reportModalLabel<?= strval($cafe['id']) ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel<?= strval($cafe['id']) ?>">Problems Report -
                    <?= htmlspecialchars($cafe['name']) ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group" id="reportOptions<?= strval($cafe['id']) ?>">
                    <button type="button" class="list-group-item list-group-item-action"
                        onclick="showReportForm(<?= strval($cafe['id']) ?>, 'name')">
                        Wrong cafe's name
                    </button>
                    <button type="button" class="list-group-item list-group-item-action"
                        onclick="showReportForm(<?= strval($cafe['id']) ?>, 'address')">
                        Wrong cafe's address
                    </button>
                    <button type="button" class="list-group-item list-group-item-action"
                        onclick="showReportForm(<?= strval($cafe['id']) ?>, 'price_range')">
                        Wrong cafe's price range
                    </button>
                    <button type="button" class="list-group-item list-group-item-action"
                        onclick="showReportForm(<?= strval($cafe['id']) ?>, 'other')">
                        Other problems
                    </button>
                </div>
                <div id="reportForm<?= strval($cafe['id']) ?>" class="mt-3" style="display: none;">
                    <form id="reportSubmitForm<?= strval($cafe['id']) ?>">
                        <div class="mb-3">
                            <label class="form-label">Current information:</label>
                            <input type="text" class="form-control" id="currentValue<?= strval($cafe['id']) ?>"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New information:</label>
                            <input type="text" class="form-control" id="proposedValue<?= strval($cafe['id']) ?>"
                                required>
                        </div>
                        <input type="hidden" id="reportType<?= strval($cafe['id']) ?>">
                        <button type="button" class="btn btn-primary"
                            onclick="submitReport(<?= strval($cafe['id']) ?>)">Send Report</button>
                        <button type="button" class="btn btn-secondary"
                            onclick="hideReportForm(<?= strval($cafe['id']) ?>)">Cancel</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-target="#cafeModal<?= strval($cafe['id']) ?>"
                    data-bs-toggle="modal">Go Back</button>
            </div>
        </div>
    </div>
</div>

<script src="/../Cafes-Viewing-Website/public/js/modal.js">
</script>
