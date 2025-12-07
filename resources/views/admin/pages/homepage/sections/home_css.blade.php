<style>
    /* ===========================================================
    HOMEPAGE BUILDER – COMPACT & CLEAN METRONIC UI
    =========================================================== */

    .section-card {
        border: 1px solid #e6e6e6;
        border-radius: .55rem !important;
        background: #fff;
        padding: 0 1.25rem;
        transition: 0.2s ease;
    }

    .section-card.dragging {
        opacity: .6;
        border-color: #0d6efd;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: grab;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .section-toggle .form-check-input {
        width: 1.3rem;
        height: 1.3rem;
    }

    /* ---- Icon Picker Modal ---- */
    #iconPickerModal .icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(85px, 1fr));
        gap: 12px;
        max-height: 350px;
        overflow-y: auto;
        padding: 10px;
    }

    #iconPickerModal .icon-item {
        border: 1px solid #ddd;
        border-radius: .4rem;
        padding: 10px 0;
        background: #f9f9f9;
        cursor: pointer;
        text-align: center;
        transition: 0.15s;
    }

    #iconPickerModal .icon-item:hover {
        background: #eef6ff;
        border-color: #0d6efd;
    }

    #iconPickerSearch {
        margin-bottom: 10px;
    }

    /* --- Repeater item cards --- */
    .repeater-box {
        border: 1px solid #eaeaea;
        padding: 1rem;
        border-radius: .45rem;
        background: #fafafa;
    }

    /* inputs compact */
    .form-control-sm,
    .form-select-sm {
        border-radius: .45rem !important;
        padding: .35rem .55rem;
        font-size: .875rem;
    }

    /* Image input wrapper (Metronic) */
    .image-input-wrapper {
        border-radius: .45rem !important;
    }

    .remove-btn {
        cursor: pointer;
        color: #dc3545;
        font-size: 1.15rem;
    }

    .remove-btn:hover {
        color: #bd2130;
    }
</style>
