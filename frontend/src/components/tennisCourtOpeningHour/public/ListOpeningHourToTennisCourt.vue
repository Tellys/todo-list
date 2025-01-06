<template>
    <section>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Dia</th>
                    <th scope="col">Horários</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(vDados, i) in getTennisCourtOpeningHourItemsToTennisCourtId?.data" :key="i">
                    <tr>
                        <th scope="row"><i class="bi bi-calendar-day me-2"></i>{{ vDados.day }}</th>
                        <td><strong>Das:</strong> ({{ vDados.hour_start }}:00) <strong>às</strong>
                            ({{ vDados.hour_end }}:00)</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </section>

</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'ListOpeningHourToTennisCourt',
    data() {
        return {
        }
    },
    props: {
        tennisCourtId: [Number, String],
    },
    mounted() {
        this.init();
    },
    computed: {
        ...mapGetters('tennisCourtOpeningHour', ['getTennisCourtOpeningHourItemsToTennisCourtId']),
    },
    methods: {
        ...mapActions('tennisCourtOpeningHour', ['tennisCourtOpeningHourItemsToTennisCourtId']),
        
        ///
        async init() {
            return await this.tennisCourtOpeningHourItemsToTennisCourtId(this.tennisCourtId ?? this.$route.params.id)
        },

    },
}
</script>
