<div class="container mt-4 mb-5">
    <h1 class="text-center mb-4">Danh mục sách</h1>

    <?php if (isset($subjects) && !empty($subjects)): ?>
        <div class="list-group shadow-sm">
            <?php
            function displayPublicSubjects($subjectsByParentArray, $parentId, $level = 0) {
                if (!isset($subjectsByParentArray[$parentId])) {
                    return;
                }
                foreach ($subjectsByParentArray[$parentId] as $subject) {
                    $indent_style = 'padding-left: ' . ($level * 20) . 'px;';
                    echo '<a href="/Book?subject_id=' . $subject->id . '" class="list-group-item list-group-item-action" style="' . $indent_style . '">';
                    echo htmlspecialchars($subject->name);
                    echo '</a>';
                    displayPublicSubjects($subjectsByParentArray, $subject->id, $level + 1);
                }
            }

            displayPublicSubjects($subjectsByParent, 0);
            displayPublicSubjects($subjectsByParent, null);
            ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">Chưa có danh mục nào.</p>
    <?php endif; ?>
</div>

<style>
.list-group-item {
    font-size: 1.1rem;
    border-radius: 0;
}
.list-group-item:first-child {
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
}
.list-group-item:last-child {
    border-bottom-left-radius: .25rem;
    border-bottom-right-radius: .25rem;
}
</style>