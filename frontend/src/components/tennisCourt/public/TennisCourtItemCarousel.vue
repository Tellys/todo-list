<template>

    <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner">
            <template v-if="item?.tennis_court_media">
                <template v-for="(vDados, i) in item?.tennis_court_media" :key="i">
                    <div class="carousel-item active" v-if="vDados.image">
                        <img loading="lazy" :src="`${endPointStorage}${vDados.image}`" class="d-block w-100"
                            :alt="vDados.name">
                    </div>

                    <div class="carousel-item active" v-else>
                        <img loading="lazy" src="https://placehold.co/600x400" class="d-block w-100" alt="...">
                    </div>
                </template>
            </template>

            <template v-else>
                <div class="carousel-item active">
                    <img loading="lazy" src="https://placehold.co/600x400" class="d-block w-100" alt="...">
                </div>
            </template>

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>

</template>


<script>

export default {
    name: 'TennisCourtItemCarousel',
    data() {
        return {
            thisClass: 'nav-link d-flex',
            tennis_court_id: this.$route.query.tennis_court_id ?? null,
            endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
        }
    },
    props: {
        item: [Object, Array],
    },
    mounted() { },
    computed: {}
}
</script>
