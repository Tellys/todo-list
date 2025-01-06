:<template>
    <div class="row d-flex align-items-center justify-content-center h-100 mb-5">
        <ul class="nav nav-tabs justify-content-between">
             <li class="nav-item">

                <template v-if="this.$route.query?.tennis_court_id">
                    <router-link :class="step == 1 ? thisClass + ' active ' : thisClass" aria-current="page"
                        :to="{ name: 'dashboardTennisCourtEdit', params: { id : this.$route.query.tennis_court_id } }"><i class="bi bi-card-list me-2"></i>
                    <span class="d-none d-sm-none d-md-block">Cadastro</span>
                    </router-link>
                </template>

                <template v-else>
                    <router-link :class="step == 1 ? thisClass + ' active ' : thisClass" aria-current="page"
                        :to="{ name: 'dashboardTennisCourtCreate'}"><i class="bi bi-card-list me-2"></i>
                    <span class="d-none d-sm-none d-md-block">Cadastro</span>
                    </router-link>
                </template>

            </li>
            <li class="nav-item">
                <template v-if="step < 2">
                    <i class="bi bi-plus-slash-minus me-2"></i>
                    <span class="d-none d-sm-none d-md-block">Complementos</span>
                </template>
                <template v-else>
                    <router-link :class="step == 2 ? thisClass + ' active ' : thisClass" aria-current="page"
                        :to="{ name: 'dashboardTennisCourtDescritpionCreate', query: { tennis_court_id : this.$route.query.tennis_court_id } }"><i
                            class="bi bi-plus-slash-minus me-2"></i>
                        <span class="d-none d-sm-none d-md-block">Complementos</span>
                    </router-link>
                </template>
            </li>
            <li class="nav-item">
                <template v-if="step < 3">
                    <i class="bi bi-images me-2"></i> <span class="d-none d-sm-none d-md-block">Fotos</span>
                </template>
                <template v-else>
                    <router-link :class="step == 3 ? thisClass + ' active ' : thisClass" aria-current="page"
                        :to="{ name: 'dashboardTennisCourtMediaCreate', query: { tennis_court_id : this.$route.query.tennis_court_id } }"><i
                            class="bi bi-images me-2"></i> <span class="d-none d-sm-none d-md-block">Fotos</span>
                    </router-link>
                </template>
            </li>
            <li class="nav-item">
                <template v-if="step < 4">
                    <i class="bi bi-calendar-day me-2"></i> <span class="d-none d-sm-none d-md-block">Horários</span>
                </template>
                <template v-else>
                    <router-link :class="step == 4 ? thisClass + ' active ' : thisClass" aria-current="page"
                        :to="{ name: 'dashboardTennisCourtOpeningHourCreate', query: { tennis_court_id : this.$route.query.tennis_court_id } }"><i
                            class="bi bi-calendar-day me-2"></i>
                        <span class="d-none d-sm-none d-md-block">Horários</span>
                    </router-link>
                </template>
            </li>
            <li class="nav-item">
                <template v-if="step < 5">
                    <i class="bi bi-cart-plus me-2"></i> <span class="d-none d-sm-none d-md-block">Produtos</span>
                </template>
                <template v-else>
                    <router-link :class="step == 5 ? thisClass + ' active ' : thisClass" aria-current="page"
                        :to="{ name: 'dashboardProductCreate', query: { tennis_court_id : this.$route.query.tennis_court_id } }"><i class="bi bi-cart-plus me-2"></i>
                        <span class="d-none d-sm-none d-md-block">Produtos</span>
                    </router-link>
                </template>
            </li>
            <li class="nav-item">
                <template v-if="step < 2">
                    <i class="bi bi-eye me-2"></i> <span class="d-none d-sm-none d-md-block">Ver o Item</span>
                </template>
                <template v-else>
                    <router-link :class="step == 6 ? thisClass + ' active ' : thisClass" aria-current="page"
                        :to="{ name: 'tennisCourtShow', params: { id : tennis_court_id} }"><i
                            class="bi bi-eye me-2"></i> <span class="d-none d-sm-none d-md-block">Ver o Item</span>
                    </router-link>
                </template>
            </li>
        </ul>
    </div>
</template>

<script>

export default {
    name: 'NavForFormCreateTennisCourt',
    data() {
        return {
            thisClass: 'nav-link d-flex',
            tennis_court_id: this.$route.query.tennis_court_id ?? null,
        }
    },
    props: {
        step: [Number, String],
    },
    mounted() {
        console.log('this.step',this.step);
    },
    computed: {}
}
</script>
