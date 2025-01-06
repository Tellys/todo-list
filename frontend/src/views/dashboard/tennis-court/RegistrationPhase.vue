<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <div>
                <h1>Lista com Pendências</h1>
                <p>A listagem abaixo mostra os itens com pendências</p>
                <ul class="list-group list-group-flush">
                    <template v-for="(vDados, i) in registrationPhases" :key="i">
                        <li class="list-group-item">
                            <router-link :to="{ name: vDados.registration_phase, params: { id: vDados.id } }">{{ vDados.name
                                }}</router-link>
                        </li>
                    </template>
                </ul>
            </div>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { mapGetters } from 'vuex';

export default {
    name: 'TennisCourtRegistrationPhaseList',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
    },
    data() {
        return {
        }
    },
    computed: {
        ...mapGetters('tennisCourt', ['registrationPhases']),
    },
    mounted() {
        console.log(this.registrationPhases);
    },
    methods: {
    },
}

</script>