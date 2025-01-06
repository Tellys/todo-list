<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">

                    <div class="row">

                        <div class="col">

                            <div class="d-flex mb-3">
                                <div class="me-2">
                                    <a v-if="items.image" href="#"><img class="rounded shadow-4-strong" alt="avatar2"
                                            height="150" loading="lazy" :src="`${endPointStorage}${items.image}`" /></a>

                                    <a v-else href="#"><img class="rounded shadow-4-strong" alt="avatar2" height="150"
                                            loading="lazy" :src="`${endPointStorage}default.png`" /></a>
                                </div>
                                <div>
                                    <h1 class="">{{ items.name }}</h1>
                                    <p>id: {{ items.id }}</p>
                                    <!-- involvement -->
                                    <tennisCourtInvolvementComponent :item="items"></tennisCourtInvolvementComponent>
                                    <!-- involvement -->
                                </div>
                            </div>


                            <div class="border p-3 mt-3">
                                <!-- TennisCourtItemCalendar -->
                                <tennisCourtItemCalendar></tennisCourtItemCalendar>
                                <!-- TennisCourtItemCalendar -->
                            </div>

                            <div class="d-grid gap-2 col-6 mx-auto my-3">
                                <button @click.prevent="onClickBtnReservar()" type="button"
                                    class="btn btn-success btn-block"><i
                                        class="bi bi-calendar-check me-2"></i>Reservar</button>
                            </div>

                            <div class="border p-3 mt-3">
                                <h3>Horários de Funcionamento</h3>
                                <listOpeningHourToTennisCourt></listOpeningHourToTennisCourt>
                            </div>

                            <div class="border p-3 mt-3">
                                <h3>Descrição</h3>
                                <p>{{ items.description }}</p>
                            </div>

                            <div class="border p-3 mt-3">
                                <h3>Localização</h3>

                                <ul class="list-unstyled">
                                    <li class="d-flex w-100">
                                        <div class="me-2"><strong>Cidade/UF:</strong></div>
                                        <div class="flex-grow-1">{{ items.city }}/{{ items.state_code }}</div>
                                    </li>

                                    <li class="d-flex w-100">
                                        <div class="me-2"><strong>Endereço:</strong></div>
                                        <div class="flex-grow-1">{{ items.address }}, {{ items.address_num }} - {{
                                            items.address_complement }} {{ items.address_neighborhood }}</div>
                                    </li>

                                    <li class="d-flex w-100">
                                        <div class="me-2"><strong>Celular:</strong></div>
                                        <div class="flex-grow-1">{{ items.cell_phone }}</div>
                                    </li>
                                </ul>
                            </div>

                        </div>

                    </div>


                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import Api from '@/services/Api';
import MyAlert from '@/services/MyAlert';


export default {
    name: 'TennisCourtShow',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
        tennisCourtInvolvementComponent: defineAsyncComponent(() =>
            import('@/components/tennisCourt/TennisCourtInvolvementComponent.vue')
        ),
        listOpeningHourToTennisCourt: defineAsyncComponent(() =>
            import('@/components/tennisCourtOpeningHour/public/ListOpeningHourToTennisCourt.vue')
        ),
        tennisCourtItemCalendar: defineAsyncComponent(() =>
            import('@/components/tennisCourtCalendar/public/TennisCourtItemCalendar.vue')
        ),
    },
    data() {
        return {
            items: {},
            id: this.$route.params.id,
            endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
        }
    },
    mounted() {
        this.getItens();
    },
    methods: {
        ///
        async getItens() {
            const response = await Api.get('tennis-court/' + this.id);
            // console.log(response);
            // console.log(response.data);
            console.log('response >>> ', response);
            return this.items = response.data;
        },

        ///
        async onClickBtnReservar() {
            var varMyAlertToast = MyAlert.init();
            varMyAlertToast.alertError('Selecione uma data no calendário!');
            return;
        }
    }
}
</script>