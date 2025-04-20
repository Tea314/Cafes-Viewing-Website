<?php
require_once __DIR__.'/../controllers/HomeController.php';
$controller = new HomeController;
$cafes = $controller->getCafes();
?>
<main>

<section class="search-section pt-3">
    <h2 class="text-main">Find a Café in Your District (HCM city only)</h2>
    <div class="container mt-3">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" id="fname" name="fname" class="form-control" placeholder="Cafe names"
                        onkeyup="showHint(this.value)">
                    <label for="fname">Find Cafe's name</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <select class="form-select" id="floatingSelect">
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
                        <option value="17">Tan Phu District</option>
                    </select>
                    <label for="floatingSelect">Choose your district</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row g-2">
                    <div class="col-3 col-md-4">
                        <button class="btn search-btn w-100">Search</button>
                            </div>
                    <div class="col-6 col-md-4">
                        <button id="sortByName" class="btn btn-outline-info w-100">Sort by Name</button>
                    </div>
                    <div class="col-3 col-md-4">
                        <button type="reset" class="btn reset-btn w-100">Reset</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    <p class="mt-2">Suggestions: <span id="txtHint"></span></p>
</section>

<section class="text-center">
    <div id="cafe-list">
        <h2 class="mb-3">List of Cafés</h2>
        <div class="container text-center">
            <div class="row g-4 justify-content-center">
                <?php if (empty($cafes)) { ?>
                    <p class="text-center">No cafés found.</p>
                <?php } else { ?>
                    <?php foreach ($cafes as $cafe) { ?>
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                            <div class="card-cafe card shadow-lg p-3 rounded-4 border-0 w-100"
                                data-bs-toggle="modal" data-bs-target="#cafeModal<?= $cafe['id'] ?>">
                                <img src="<?= htmlspecialchars($cafe['images'][0]) ?>" class="card-img-top align-center p-2 rounded-5"
                                    style="width: 100%; object-fit: cover; height: 24em;"
                                    alt="<?= htmlspecialchars($cafe['name']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title pt-0"><?= htmlspecialchars($cafe['name']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($cafe['description']) ?></p>

                                    <?php if (! empty($cafe['tags'])) { ?>
                                        <div class="mt-2">
                                            <?php foreach (array_slice($cafe['tags'], 0, 3) as $tag) { ?>
                                                <span class="badge bg-info bg-gradient"><?= htmlspecialchars($tag['name']) ?></span>
                                            <?php } ?>
                                            <?php if (count($cafe['tags']) > 3) { ?>
                                                <span class="badge bg-info bg-gradient bg-opacity-75">+<?= count($cafe['tags']) - 3 ?></span>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>



<div class="d-flex justify-content-center m-4 p-3">
    <button id="loadMore" class="btn">Load More</button>
</div>
</main>
<div id="modal-container">
    <?php if (! empty($cafes)) { ?>
        <?php foreach ($cafes as $cafe) { ?>
            <?= $cafe['modalHtml'] ?>
        <?php } ?>
    <?php } ?>
</div>
<script src="<?= BASE_URL ?>/js/lazyload.js" defer></script>
<script src="<?= BASE_URL ?>/js/sort.js"></script>
<script src="<?= BASE_URL ?>/js/search.js"></script>
<script>
        function showHint(str) {
            if (str.length == 0) {
                document.getElementById("txtHint").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("txtHint").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "../app/controllers/HintController.php?q=" + encodeURIComponent(str), true);
                xmlhttp.send();
            }
        }

        function showLoadMore() {
            document.getElementById("loadMore").style = "";
        }
    </script>

