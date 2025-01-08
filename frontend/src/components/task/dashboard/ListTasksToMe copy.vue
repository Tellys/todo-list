<template>
    <section>
        <div class="d-flex flex-column justify-content-center">

            <table v-if="getTasksToMe?.success" class="table">
                <thead>
                    <tr>
                        <th scope="col">Titulo</th>
                        <th scope="col">Level</th>
                        <th scope="col">Criado em</th>
                        <th scope="col">Vence em</th>
                        <th scope="col">CMD</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(vDados, i) in getTasksToMe?.data" :key="i">
                        <tr>
                            <th scope="row">{{ vDados.title }}</th>
                            <td>{{ vDados.level }}</td>
                            <td class="date-to-format" :id="'createdAt'+vDados.id" :data-date="vDados.created_at"></td>
                            <td class="date-to-format" :id="'expires_at'+vDados.id" :data-date="vDados.expires_at"></td>
                            <td>                                
                                <button @click.prevent="this.$store.dispatch('task/checkItem', vDados)" type="button" class="btn text-success"><i class="fa-solid fa-check"></i></button>
                                <button @click.prevent="this.$router.push({name:'dashboardTaskEdit', params:{id:vDados.id}, query:{redirect, id:id}})" type="button" class="btn text-primary"><i
                                        class="bi bi-pencil"></i></button>
                                <button @click.prevent="this.$store.dispatch('task/deleteItem', vDados)" type="button" class="btn text-danger"><i
                                        class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </section>

</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import moment from 'moment';

export default {
    name: 'ListTasksToMe',
    data() {
        return {
            redirect:null,
        }
    },
    props: {
        tennisCourtId: [Number, String],
    },
    async mounted() {
        await this.init();
        await this.dateFormat();
    },
    computed: {
        ...mapGetters('task', ['getTasksToMe']),
    },
    methods: {
        ...mapActions('task', ['tasksItemsToMe']),

        /// 
        async init() {
            //this.redirect = '/dashboard/task/create/?id='+this.userId
            this.redirect = '/';
            await this.tasksItemsToMe()
            //console.log(this.getTasksToMe)

            return;
        },

        async dateFormat(className ='.date-to-format' ){
            const allEl = document.querySelectorAll(className);
            allEl.forEach(el => {
                el.innerHTML = el.dataset.date ? moment(el.dataset.date).format('DD/MM/YYYY HH:mm') : 'n/a';
            });
            return 
        },
    },
}
</script>
