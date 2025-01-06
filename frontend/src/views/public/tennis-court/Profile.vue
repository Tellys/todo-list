<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <p class="text-center">Abaixo estão listados os dados do item.</p>

                        <div class="flex-sm-column rounded" >
                            <img :src="userAvatar" class="rounded" :alt="items.name" style="max-width:15%">
                        </div>
                        
                        <h1 class="text-center">{{ items.name }} - id: {{ items.id }}</h1>

                        <p class="text-center">
                            <a :href="`/tennis-court/${items.id}/edit`" class="btn btn-warning" title="Editar Dados">
                                <i class="bi bi-pencil-fill"></i> Editar dados
                            </a>
                        </p>

                        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                            <ul v-for="(item, i) in items" :key="i">
                                <li v-if="i !== 'cnae'
                                    && i !== 'id'
                                    && i !== 'name'
                                    && i !== 'email_verified_at'
                                    //&& i !== 'two_factor_secret'
                                    //&& i !== 'two_factor_recovery_codes'
                                    && i !== 'image'
                                    && i !== 'social'
                                    && i !== 'slug'
                                    && i !== 'description'
                                    "> <strong>{{ i }}: </strong> {{ item }}</li>
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

export default {    name: 'TennisCourtShow',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),},
    data() {
        return {
            items: {},
            urlStorage: process.env.VUE_APP_API_URL_STORAGE,
            userAvatar: process.env.VUE_APP_USER_DEFAULT_IMAGE,
        }
    },
    mounted() {
        this.getItens();
    },
    methods: {
        async getItens() {
            const response = await Api.get('tennis-court/profile');
            console.log(response);

            if (response.data.image) {
                this.userAvatar = this.urlStorage + response.data.image;
            }

            return this.items = response.data;
        }
    }
}
</script>