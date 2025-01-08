<template>
    <router-link v-if="myLink" :class="myClass" :href="myLink" :role="myType">
        <slot></slot>
    </router-link>

    <button v-else :type="myType" :class="myClass">
        <slot name="slotLeft"></slot>
        <i v-html=myIcon></i>
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: 'BtnDefault',
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
            default: 'btn btn-default',
        },
        myValue: {
            type: String,
            default: null,
        },
        myIcon: {
            type: String,
            default: null,
        }
    }
}
</script>