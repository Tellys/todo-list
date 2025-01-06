<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
               
            </section>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { mapGetters } from 'vuex';

export default {
    name: 'TennisCourtShow',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
    },
    data() {
        return {
            register: {},
            //id: this.$route.params.id,
            //endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
        }
    },
    computed: {
        ...mapGetters('cart', ['getItemsCart', 'getValueTotalOfCart', 'getTotalOfItemsIntoCart']),
    },
    mounted() {
        this.init();
    },
    methods: {
        async init() {
        },
    }
}
</script>