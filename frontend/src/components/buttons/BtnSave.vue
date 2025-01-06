<template>
    <router-link v-if="myLink" :class="myClass" :href="myLink" :role="myType">
        <slot></slot>
    </router-link>

    <button v-else :type="myType" :class="myClass">
        <slot name="slotLeft"></slot>
        <svg xmlns="http://www.w3.org/2000/svg" width="16"
            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
            <path
                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
        </svg>
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: 'BtnSave',
    props: {
        myLink: {
            type: String,
            default: null,
        },
        myType: {
            type: String,
            default: 'button',
        },
        myClass: {
            type: String,
            default: 'btn btn-success',
        },
        myValue: {
            type: String,
            default: null,
        },
    }
}
</script>