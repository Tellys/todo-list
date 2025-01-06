<template>
    <section>
        <div class="d-flex justify-content-center">
            <template v-for="(vDados, i) in getMediaForTennisCourtId?.data" :key="i">
                <div class="me-3 border rounded shadow p-3">
                    <div class="d-flex justify-content-center">
                        <button @click.prevent="decrement(vDados)" type="button" class="btn"><i
                                class="bi bi-arrow-left-short"></i></button>
                        <button @click.prevent="remove(vDados)" type="button" class="btn text-danger"><i
                                class="bi bi-trash"></i></button>
                        <button @click.prevent="increment(vDados)" type="button" class="btn"><i
                                class="bi bi-arrow-right-short"></i></button>
                    </div>
                    <div class="d-flex justify-content-center">
                        <img v-if="vDados.image" class="" :alt="vDados.name" height="150"
                            loading="lazy" :src="`${endPointStorage}${vDados.image}`" />

                        <img v-else class="rounded shadow-4-strong" alt="avatar2" height="150" loading="lazy"
                            :src="`${endPointStorage}default.png`" />
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ vDados.name }}
                    </div>
                </div>

            </template>
        </div>
    </section>

</template>

<script>
import store from '@/store';
import { mapGetters } from 'vuex';

export default {
    name: 'ListMediaForTennisCourt',
    data() {
        return {
            endPointStorage: process.env.VUE_APP_API_URL_STORAGE,
        }
    },
    props: {
        tennisCourtId: [Number, String],
    },
    mounted() {
        this.init();
    },
    computed: {
        ...mapGetters('tennisCourtMedia', ['getMediaForTennisCourtId']),
    },
    methods: {
        ///
        async init() {
            return await store.dispatch('tennisCourtMedia/getMediaForTennisCourtId', this.tennisCourtId)
        },

        ///
        async increment(item) {
            delete item.order;
            delete item.created_at;
            delete item.deleted_at;

            return await store.dispatch('tennisCourtMedia/updateItem', { ...item, ...{ increment: 'order' } })
        },

        ///
        async decrement(item) {
            delete item.order;
            delete item.created_at;
            delete item.deleted_at;

            return await store.dispatch('tennisCourtMedia/updateItem', { ...item, ...{ decrement: 'order' } })
        },

        ///
        async remove(id) {
            return await store.dispatch('tennisCourtMedia/deleteItem', id)
        },
    },
}
</script>
