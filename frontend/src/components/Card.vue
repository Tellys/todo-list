<template>
    <!-- <div class="card" v-for="vDados in dados.data" :key="vDados.id">
        <h2 class="title">{{ vDados.name }}</h2>
        <p class="text">{{ vDados.detail }}</p>
        <p class="price">{{ vDados.created_at }}</p>
    </div> -->
    <div class="col" v-for="vDados in dados.data" :key="vDados.id">
        <div class="card h-100 text-center">
            <div class="text-center">
                <img id="main-image" src="@/assets/placeholder-image.png" width="250" @click="viewItem(vDados.id)" style="cursor: pointer" />
            </div>
            <!-- <div class="thumbnail text-center"> <img onclick="change_image(this)" src="@/assets/placeholder-image.png" width="70"> <img onclick="change_image(this)" src="@/assets/placeholder-image.png" width="70"> </div> -->
            <div class="card-body" @click="viewItem(vDados.id)" style="cursor: pointer">
                <h5 class="card-title text-uppercase">{{ vDados.name }}</h5>
                <div class=""> 
                    <div class="w-100 display-4 act-price">R$ {{ vDados.price }}</div>
                    <div class="w-100 text-left"> Estoque:<span>{{ vDados.estoque }}</span> </div>
                </div>
                <p class="card-text">
                    {{ vDados.detail }}
                </p>
            </div>
            <div class="card-footer text-center p-4">
                <button class="btn btn-danger text-uppercase mr-2 px-4" @click="comprarItem(vDados.id, vDados.price, 1)">Comprar</button> <i class="fa fa-heart text-muted"></i> <i class="fa fa-share-alt text-muted"></i>
            </div>
        </div>
    </div>
        

</template>
 

<script>
/*
function change_image(image){
    var container = document.getElementById("main-image");
    container.src = image.src;
    }
    document.addEventListener("DOMContentLoaded", function(event) {
    });
*/

import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: 'Card',
    props:{
        dados: Object,
        /* id: Number,
        price: String,
        quantidade: Number, */
    },
    components: {
    },
    data() {
        return {
            /* register: {
                id: this.dados.id,
                price: this.dados.price,
                quantidade: this.dados.quantidade,
            }, */
            error: "",
            data:[],
        }
    },
    methods: {
        comprarItem(id, price, quantidade){
            axios.post('pedidos', {'products_id':id, 'price':price, 'quantidade':quantidade})
                .then(response => {
                    this.error = false;
                    this.data = response.data;

                    if (response.status=== 200) {
                        Swal.fire({
                            title: 'Sucesso',
                            text:   response.data.message,
                            icon: 'success',                        
                        })
                        // .then(function () {
                        //     this.$router.push('/products');
                        // })
                        ;                                           
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'OPPS',
                        text:   error.response.data.message,
                        icon: 'warning',                        
                    });
                    
                    this.data = '';
                    this.error = {
                        status: error.response.data.message,
                        message: error.response.data.message
                    };
                    console.log(error.response);
                })
        },
        async viewItem(id){
            this.$router.push('/products/'+id);
        },
    }
}
</script>