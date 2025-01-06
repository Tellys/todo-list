<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Complementos do Item</h1>
                        <p class="text-center">Casdastro Complementos do Item.</p>
                    </div>
                </div>
            </section>

            <section>
                <div class="container p-5 h-100 bg-light">

                    <div class="row d-flex align-items-center justify-content-center h-100 mb-5">
                        <ul class="nav nav-tabs justify-content-between">
                            <li class="nav-item">
                                <router-link class="nav-link d-flex" aria-current="page"
                                    :to="{ name: 'dashboardTennisCourtEdit', params: { id: this.$route.params.id } }"><i
                                        class="bi bi-card-list me-2"></i>
                                    <span class="d-none d-sm-none d-md-block">Cadastro</span>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <div class="nav-link active d-flex"><i class="bi bi-plus-slash-minus me-2"></i> <span
                                        class="d-none d-sm-none d-md-block">Complementos</span></div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex" href="#"><i class="bi bi-images me-2"></i> <span
                                        class="d-none d-sm-none d-md-block">Fotos</span></a>
                            </li>
                            <li class="nav-item">
                                <!-- <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a> -->
                                <a class="nav-link d-flex" href="#"><i class="bi bi-eye me-2"></i> <span
                                        class="d-none d-sm-none d-md-block">Ver o Item</span></a>
                            </li>
                        </ul>
                    </div>

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm :myUri="myUri" myFormMethod="post"
                        :routeNameSuccessRedirect="{ name: 'dashboardTennisCourtCalendarList', params: { id: this.routeId } }">
                    </DynamicForm>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'TennisCourtCalendarCreate',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm.vue')
        ),
    },
    data() {
        return {
            myUri: 'tennis-court-calendar/create',
            routeId: this.$route.params.id ?? null,
        }
    },
    async mounted() {
        this.init()
    },
    methods: {
        init() {
            if (this.$route.params?.id) {
                return this.myUri += `?id=${this.$route.params.id}`;
            }
            return false
        }
    },

}

</script>
