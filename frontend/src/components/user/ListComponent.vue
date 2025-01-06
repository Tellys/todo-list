<template>
    <div>
        <p>{{ title }} <small>(Qtd de Cadstros = {{ itens.total }})</small></p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" v-for="(lColumns, index) in columnsLabel " :key="index">{{ lColumns }}</th>
                    <th>CMD</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in itens.data" :key="user">
                    <td v-for="(fColumns, index) in columnsField " :key="index">{{ user[fColumns] }}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="buttonsTable">
                            <BtnShow @click.prevent="viewItem(user.id)"  />
                            <BtnEdit @click.prevent="editItem(user.id)"  />
                            <BtnDelete @click.prevent="deleteItem(user.id)"  />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import BtnDelete from '@/components/buttons/BtnDelete.vue';
import BtnEdit from '@/components/buttons/BtnEdit.vue';
import BtnShow from '@/components/buttons/BtnShow.vue';

import Swal from 'sweetalert2';
import axios from 'axios';


export default {
    name: 'UsersListComponent',
    components: {
        BtnDelete, BtnEdit, BtnShow
    },
    props: {
        itens: Object,
        title: String,
        columnsLabel: Array,
        columnsField: Array,
    },
    data() {
        return {
            pages: [],
        }
    },
    mounted() {
        this.init();
    },
    methods: {
        async init() {
        },
        async editItem(id) {
            this.$router.push('/user/' + id + '/edit');
        },
        async viewItem(id) {
            this.$router.push('/user/' + id);
        },
        async deleteItem(id) {
            Swal.fire({
                title: 'Confirma a exlusão do item?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('user/' + id)
                        .then((response) => {
                            if (response.status === 200) {
                                Swal.fire({
                                    title: 'Item Deletado',
                                    icon: 'success',
                                });
                            } else {
                                Swal.fire({
                                    title: 'OPPS',
                                    text: response.data.message,
                                    icon: 'warning',
                                });
                            }
                        })
                } /* else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
          } */
            }).then(() => {
                this.init();
            })
        }
    },
}
</script>