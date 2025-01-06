import router from "@/router"
import Api from "@/services/Api"
import MyAlert from "@/services/MyAlert"

// initial state
const state = () => ({
  getItemsCart: null,
  getTotalOfItemsIntoCart: null,
  getValueTotalOfCart: null,
  getCartPaymentMethodSelected: null,
})

// getters
const getters = {
  getItemsCart: state => state.getItemsCart,
  getTotalOfItemsIntoCart: state => state.getTotalOfItemsIntoCart,
  getValueTotalOfCart: state => state.getValueTotalOfCart,
  getCartPaymentMethodSelected: state => state.getCartPaymentMethodSelected,
}

// actions
const actions = {

  ///
  async getItemsCart({ commit }) {
    await Api.get('cart/all').then(async (r) => {
      return await commit('GET_ITEMS_CART', r);
    })
  },

  ///
  async updateItem({ dispatch }, data) {
    await Api.update('cart/' + data.id, data).then(async () => {
      return await dispatch('getItemsCart', data.tennis_court_id);
    })
  },

  ///
  async deleteItem({ dispatch }, id) {
    await Api.forceDelete('cart/' + id + '/force-delete').then(async () => {
      return await dispatch('getItemsCart');
    })
  },

  ///

  /* let r = {
    "status": 200,
    "success": true,
    "data": {
      "cart": [
        {
          "id": 3,
          "status": "shopping",
          "tennis_court_calendar_id": 173,
          "product_id": 1,
          "product_name": "Aluguel de quadra",
          "qty": "1.00",
          "price": "64.00",
          "price_promo": null,
          "discount": null,
          "discount_justification": null,
          "discount_policy_id": 1,
          "client_id": 1,
          "user_id": 1,
          "deleted_at": null,
          "created_at": "2024-08-02T10:52:06.000000Z",
          "updated_at": "2024-08-02T10:52:06.000000Z",
          "customer_request_id": null,
          "tennis_court_calendar": {
            "id": 173,
            "tennis_court_id": 1,
            "time_start": "2024-08-17 12:00:00",
            "time_end": "2024-08-17 13:00:00",
            "status": "reserved",
            "user_id": 1,
            "deleted_at": null,
            "created_at": "2024-08-02T10:52:06.000000Z",
            "updated_at": "2024-08-02T10:52:06.000000Z",
            "tennis_court_opening_hour_id": null,
            "tennis_court_opening_hour": null
          },
          "product": {
            "id": 1,
            "price": "0.00",
            "price_promo": null,
            "tennis_court_id": 1,
            "user_id": 1,
            "deleted_at": null,
            "created_at": null,
            "updated_at": null,
            "products_default_id": 1
          },
          "discount_policy": {
            "id": 1,
            "name": "default",
            "description": "default",
            "user_id": 1,
            "deleted_at": null,
            "created_at": null,
            "updated_at": null
          },
          "customer_request": null,
          "client": {
            "id": 1,
            "name": "User Diretor Test",
            "cpf": "35484709040",
            "email": "diretor@mail.com",
            "cell_phone": "68505673332"
          },
          "user": {
            "id": 1,
            "name": "User Diretor Test",
            "cpf": "35484709040",
            "email": "diretor@mail.com",
            "cell_phone": "68505673332"
          }
        },
        {
          "id": 4,
          "status": "shopping",
          "tennis_court_calendar_id": 174,
          "product_id": 1,
          "product_name": "Aluguel de quadra",
          "qty": "1.00",
          "price": "64.00",
          "price_promo": null,
          "discount": null,
          "discount_justification": null,
          "discount_policy_id": 1,
          "client_id": 1,
          "user_id": 1,
          "deleted_at": null,
          "created_at": "2024-08-02T10:52:06.000000Z",
          "updated_at": "2024-08-02T10:52:06.000000Z",
          "customer_request_id": null,
          "tennis_court_calendar": {
            "id": 174,
            "tennis_court_id": 1,
            "time_start": "2024-08-17 14:00:00",
            "time_end": "2024-08-17 15:00:00",
            "status": "reserved",
            "user_id": 1,
            "deleted_at": null,
            "created_at": "2024-08-02T10:52:06.000000Z",
            "updated_at": "2024-08-02T10:52:06.000000Z",
            "tennis_court_opening_hour_id": null,
            "tennis_court_opening_hour": null
          },
          "product": {
            "id": 1,
            "price": "0.00",
            "price_promo": null,
            "tennis_court_id": 1,
            "user_id": 1,
            "deleted_at": null,
            "created_at": null,
            "updated_at": null,
            "products_default_id": 1
          },
          "discount_policy": {
            "id": 1,
            "name": "default",
            "description": "default",
            "user_id": 1,
            "deleted_at": null,
            "created_at": null,
            "updated_at": null
          },
          "customer_request": null,
          "client": {
            "id": 1,
            "name": "User Diretor Test",
            "cpf": "35484709040",
            "email": "diretor@mail.com",
            "cell_phone": "68505673332"
          },
          "user": {
            "id": 1,
            "name": "User Diretor Test",
            "cpf": "35484709040",
            "email": "diretor@mail.com",
            "cell_phone": "68505673332"
          }
        }
      ],
      "customer_request": {
        "id": 1,
        "status": "processing",
        "price": "128.00",
        "price_promo": "128.00",
        "discount": null,
        "discount_justification": null,
        "payment_log": "{\"calendario\":{\"criacao\":\"2024-07-31T20:23:04.180Z\",\"expiracao\":3600},\"txid\":\"c90553932eda4d0db9484e7340ba4eff\",\"revisao\":0,\"status\":\"ATIVA\",\"valor\":{\"original\":\"138.00\"}}{\"calendario\":{\"criacao\":\"2024-07-31T20:24:03.418Z\",\"expiracao\":3600},\"txid\":\"1b5a20b748cb483f8a408861fefd8ddb\",\"revisao\":0,\"status\":\"ATIVA\",\"valor\":{\"original\":\"138.00\"}}",
        "discount_policy_id": 1,
        "client_id": 1,
        "user_id": 1,
        "deleted_at": null,
        "created_at": "2024-07-31T20:23:02.000000Z",
        "updated_at": "2024-08-02T10:52:14.000000Z",
        "payment_method_id": 1,
        "discount_policy": {
          "id": 1,
          "name": "default",
          "description": "default",
          "user_id": 1,
          "deleted_at": null,
          "created_at": null,
          "updated_at": null
        },
        "payment_method": {
          "id": 1,
          "name": "pix",
          "type": "pix",
          "status": "operational",
          "financial_institution": "Nome do Banco",
          "rate": "6.00",
          "deadline_for_receipt": "0000-00-00 00:00:00",
          "description": "Descrição do tipo de pagamento",
          "user_id": 1,
          "deleted_at": null,
          "created_at": null,
          "updated_at": null
        },
        "client": {
          "id": 1,
          "image": null,
          "name": "User Diretor Test",
          "name_corporate": null,
          "birthday": "1980-06-27 00:00:00",
          "cpf": "35484709040",
          "slug": null,
          "email": "diretor@mail.com",
          "email_verified_at": "2024-07-31T20:22:10.000000Z",
          "country": "Brasil",
          "country_code": "BR",
          "state": "Minas Gerais",
          "state_code": "MG",
          "city": "Três Corações",
          "zip_code": 37611341,
          "address": "Bianka Field",
          "address_neighborhood": "Lilly",
          "address_num": 6392,
          "address_complement": null,
          "phone": "23181334390",
          "cell_phone": "68505673332",
          "cell_phone_verified_at": null,
          "type_login": null,
          "web_site": null,
          "lat": "-21.37965350",
          "lng": "-45.51577680",
          "description": "Rem magnam eius dicta sit rem et atque. Veritatis consequatur non dolor maiores molestiae ab. Impedit voluptatum ut pariatur amet culpa. Enim neque necessitatibus vero in aperiam sapiente.",
          "created_at": "2024-07-31T20:22:10.000000Z",
          "updated_at": "2024-07-31T20:22:10.000000Z",
          "deleted_at": null,
          "identidade_civil": null,
          "identidade_civil_emissor": null,
          "identidade_civil_emissor_uf": null,
          "user_id": 1,
          "users_level_id": 1
        },
        "user": {
          "id": 1,
          "image": null,
          "name": "User Diretor Test",
          "name_corporate": null,
          "birthday": "1980-06-27 00:00:00",
          "cpf": "35484709040",
          "slug": null,
          "email": "diretor@mail.com",
          "email_verified_at": "2024-07-31T20:22:10.000000Z",
          "country": "Brasil",
          "country_code": "BR",
          "state": "Minas Gerais",
          "state_code": "MG",
          "city": "Três Corações",
          "zip_code": 37611341,
          "address": "Bianka Field",
          "address_neighborhood": "Lilly",
          "address_num": 6392,
          "address_complement": null,
          "phone": "23181334390",
          "cell_phone": "68505673332",
          "cell_phone_verified_at": null,
          "type_login": null,
          "web_site": null,
          "lat": "-21.37965350",
          "lng": "-45.51577680",
          "description": "Rem magnam eius dicta sit rem et atque. Veritatis consequatur non dolor maiores molestiae ab. Impedit voluptatum ut pariatur amet culpa. Enim neque necessitatibus vero in aperiam sapiente.",
          "created_at": "2024-07-31T20:22:10.000000Z",
          "updated_at": "2024-07-31T20:22:10.000000Z",
          "deleted_at": null,
          "identidade_civil": null,
          "identidade_civil_emissor": null,
          "identidade_civil_emissor_uf": null,
          "user_id": 1,
          "users_level_id": 1
        }
      },
      "payment_method_selected": {
        "payment_type": "pix",
        "http_code": 200,
        "qrcode": "00020101021226850014BR.GOV.BCB.PIX2563qrcodespix-h.sejaefi.com.br/v2/bac2f996042843ca92fbbe0269f00b2b5204000053039865802BR5905EFISA6008SAOPAULO62070503***6304B109",
        "imagemQrcode": "data:image/png;base64,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",
        "linkVisualizacao": "https://pix.sejaefi.com.br/cob/pagar/bac2f996042843ca92fbbe0269f00b2b"
      }
    },
    "message": "Forma de pagamento"
  } */ 

  async formSubmit({ getters, commit }, data) {
    var totalPrice = { total_price: getters.getValueTotalOfCart };
    await Api.post('cart/pay', { ...totalPrice, ...data }).then(async (r) => {      
      //'payment_type' = ['cartao_de_credito' | 'pix' | 'boleto']
      if (r?.data?.payment_method?.type == 'pix') {
        //await commit('GET_CART_PAYMENT_METHOD', r.data.payment_method_selected);
        await commit('customerRequest/SET_CUSTUMER_REQUEST', r.data, { root: true });
        //await commit('customerRequest/SET_CUSTUMER_REQUEST_PAYMENT_METHOD_SELECTED', r.data.payment_method_selected, { root: true });

        router.push({ name: 'dashboardCustomerRequestShow', params: {id: r.data.id}});
      }
      var varMyAlert = MyAlert.init();
      return varMyAlert.alertError(varMyAlert.displayError(r));
    })
  },
}

// mutations
const mutations = {
  GET_ITEMS_CART: (state, v) => {

    if (v?.data?.total_price && !Array.isArray(v?.data?.total_price)) {
      state.getValueTotalOfCart = v?.data?.total_price
      delete (v.data.total_price);

      state.getItemsCart = v.data
      state.getTotalOfItemsIntoCart = Object.keys(v.data).length
    }
  },

  ///
  GET_CART_PAYMENT_METHOD: (state, v) => {
    state.getCartPaymentMethodSelected = v
  },
}

/// fim
export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}