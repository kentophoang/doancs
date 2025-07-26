<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Khám phá Thư viện</h1>
        <p class="lead text-muted">Duyệt qua các danh mục để tìm tài liệu bạn cần.</p>
    </div>

    <div class="accordion" id="categoryAccordion">
        <?php if (!empty($mainCategories)): ?>
            <?php foreach ($mainCategories as $index => $mainCategory): ?>
                <div class="accordion-item shadow-sm mb-3">
                    <h2 class="accordion-header" id="heading-<?= $mainCategory->id ?>">
                        <button class="accordion-button collapsed fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $mainCategory->id ?>">
                            <?= htmlspecialchars($mainCategory->name) ?>
                        </button>
                    </h2>
                    <div id="collapse-<?= $mainCategory->id ?>" class="accordion-collapse collapse" data-bs-parent="#categoryAccordion">
                        <div class="accordion-body">
                            <!-- Nếu là danh mục "Học tập", hiển thị các Khoa con -->
                            <?php if (!empty($facultiesByMainCategory[$mainCategory->id])): ?>
                                <div class="list-group">
                                    <?php foreach ($facultiesByMainCategory[$mainCategory->id] as $faculty): ?>
                                        <div class="list-group-item">
                                            <h6 class="fw-bold mb-2"><?= htmlspecialchars($faculty->name) ?></h6>
                                            <?php if (!empty($subjectsByFaculty[$faculty->id])): ?>
                                                <div class="d-flex flex-wrap">
                                                    <?php foreach ($subjectsByFaculty[$faculty->id] as $subject): ?>
                                                        <a href="/Book?subject_id=<?= $subject->id ?>" class="btn btn-outline-secondary btn-sm me-2 mb-2"><?= htmlspecialchars($subject->name) ?></a>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted small fst-italic">Chưa có môn học trong khoa này.</p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <!-- Ngược lại, hiển thị các chủ đề con trực tiếp -->
                            <?php elseif (!empty($subjectsByMainCategory[$mainCategory->id])): ?>
                                <div class="list-group">
                                    <?php foreach ($subjectsByMainCategory[$mainCategory->id] as $subject): ?>
                                        <a href="/Book?subject_id=<?= $subject->id ?>" class="list-group-item list-group-item-action"><?= htmlspecialchars($subject->name) ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Chưa có chủ đề nào trong danh mục này.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<style>
    .accordion-button:not(.collapsed) { color: #0d6efd; background-color: #e7f1ff; }
    .accordion-button:focus { box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); }
    .accordion-item { border: none; border-radius: .5rem !important; }
</style>
