<template>
    <!-- Button trigger modal -->
    <button
        type="button"
        class="buttonStyle"
        data-bs-toggle="modal"
        data-bs-target="#exampleModal"
    >
        {{ buttonText }}
    </button>

    <!-- Modal -->
    <div
        class="modal fad"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog top_bg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Modal title
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">...</div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>
                    <button type="button" class="btn btn-primary">
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
//Imports

//Uses

//Refs

//Props $ Emit
defineProps({
  buttonText: String,
})
//Computed

//Mathods

//Hooks
</script>
<style scoped>
@import 'bootstrap/dist/css/bootstrap.min.css';
@import '../../../css/button.css';
@import '../../../css/top_bg.css';
</style>
