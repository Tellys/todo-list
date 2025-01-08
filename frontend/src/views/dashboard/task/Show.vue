<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <p class="text-center">Abaixo estão listados os dados do item.</p>

                        <h1 class="text-center">{{ items.title }} # id: {{ items.id }}</h1>

                        <p class="text-center"><router-link :to="{ name: 'dashboardTaskEdit', params: { id: items.id } }"
                                class="btn btn-warning">
                                <i class="bi bi-pencil-fill"></i> Editar dados
                            </router-link>
                        </p>

                        <div class="text-center">
                            <a v-if="items.image" href="#"><img class="rounded-circle shadow-4-strong" alt="avatar2"
                                    height="150" loading="lazy" :src="`${endPointStorage}${items.image}`" /></a>
                        </div>

                        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                            <ul v-for="(item, i) in items" :key="i">
                                <li v-if="
                                    i !== 'cnae'
                                    && i !== 'id'
                                    && i !== 'name'
                                    && i !== 'email_verified_at'
                                    && i !== 'social'
                                    && i !== 'slug'
                                    && i !== 'description'
                                    && i !== 'created_at'
                                    && i !== 'updated_at'
                                    && i !== 'deleted_at'
                                    && i !== 'expires_at'
                                "> <strong>{{ i }}: </strong> {{ item }}</li>

                                <li v-if="i == 'created_at' || i == 'updated_at' || i == 'expires_at'">
                                    <strong>{{ i }}: </strong> <span class="date-to-format" :data-date="item"></span>
                                </li>
                            </ul>

                            <h2 v-if="items.cnae">Cnae</h2>

                            <ul v-for="(itemCnae, iCnae) in items.cnae" :key="iCnae">
                                <li>
                                    <strong>{{ itemCnae.type }}: </strong> {{ itemCnae.name }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import Api from '@/services/Api';
import { defineAsyncComponent } from 'vue';
import moment from 'moment';

export default {
    name: 'TaskShow',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/dashboard/Template.vue')
        ),
    },
    data() {
        return {
            items: {},
            id: this.$route.params.id,
            endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
        }
    },
    async mounted() {
        await this.getItens();
        await this.dateFormat()
    },
    methods: {
        async getItens() {
            const response = await Api.get('task/' + this.id);
            console.log('response >>> ', response);
            return this.items = response.data;
        },

        async dateFormat(className ='.date-to-format' ){
            const allEl = document.querySelectorAll(className);
            console.log('allEl', allEl);

            allEl.forEach(el => {
                console.log('el.dataset.date', el.dataset.date);
                el.innerHTML = el.dataset.date ? moment(el.dataset.date).format('DD/MM/YYYY HH:mm') : 'n/a';
            });
            return 
        },
    }
}
</script>