<script>
import {store} from '../../data/store';
import tt from '@tomtom-international/web-sdk-maps';
import axios from 'axios';

export default {
    name: 'Apartment',

    data(){
        return{
          apartment : store.apartmentDetails,
          author_first_name: "",
          author_last_name: "",
          author_email: "",
          apartment_id: '',
          text: "",
          object:'',
          errors : {},
          success : false,
          sending : false
        }
    },
    components:{},
    methods :{
      getImage(img){
        return new URL ()
      },
      sendMail(){
        this.sending = true

        const data = {
          author_first_name: this.author_first_name,
          author_last_name: this.author_last_name,
          author_email: this.author_email,
          text: this.text,
          apartment_id : this.apartment.id,
          object : this.object
        }
        sending :false

        axios.post(store.apiHostUrl +'/contacts',data)
        .then(result => {
          this.sending = false
          this.success = result.data.success
          console.log('datacompilato->',data)
          console.log('log->',result.data)

          if (result.data.success) {
            this.errors = {};
          }else{
            this.errors = result.data.errors;
          }
          // setTimeout(() => {
          //   this.success = false;
          //   this.author_first_name = '';
          //   this.author_last_name = '';
          //   this.senders_email = '';
          //   this.message = '';
          // }, 10000);

        })
      },
      // tomtom map----------------------------------------------------------------\

      initializeMap() {
        store.advSrcRequest.type='adv';
        // se arrivi direttamente in advanced search allora centra la mappa su Roma
        // if(store.advSrcRequest.radius === '?'){
        //   store.advSrcRequest.radius = 20;
        // }

        // if(!store.mapCoord){
        //   store.mapCoord = [12.49427, 41.89056];
        //   console.warn('centro mappa mancante')
        // }

        // reset dom---
        const mapDiv = document.getElementById('map');
        mapDiv.innerHTML = '';

        // inizializza mappa
        map = tt.map({
        key: store.apiKey,
        container: 'map',
        center: [ store.apartmentDetails.longitude, store.apartmentDetails.latitude,],
        zoom: 13,
        pitch: true, // Abilita l'animazione --- D: ...ma non funziona!!! :(
        animate: true, // nada--- :'(
        });

        map.addControl(new tt.FullscreenControl());
        map.addControl(new tt.NavigationControl());

        map.on('load', () => {
            new tt.Marker().setLngLat([store.apartmentDetails.longitude, store.apartmentDetails.latitude]).addTo(map);
        });
      },

    },
    mounted(){
      console.log('whereIam?', this.$route.name );
      console.log(store.apartmentDetails );
      this.initializeMap();
    }
}
</script>

<template>
    <div class="container py-3" id="apartment-page">

      <!--Titolo -->
      <div class="py-2">
        <h1> <b>{{ apartment.name }}</b></h1>
        <span><i class="fa-solid fa-star"></i> 5,0</span>
        <span class="card-text mx-2"><b>{{ apartment.address }}</b></span>
      </div>

      <div class="apartment_top " >

        <!-- Immagine -->

          <img :src="'/storage/' + apartment.cover_image" alt="">

        <!-- mappa -->
        <div class="mapping">
          <div id="mountMap" class="w-100 h-100">
              <div class="map w-100 h-100" id="map" ref="mapRef">MAPPA</div>
          </div>
        </div>

      </div>

      <!-- Testo -->
      <div class="info py-4">

        <div v-show="apartment.address_info">

          <h5>{{ apartment.name }}</h5>
          <div class="" role="alert">

            <p>{{ apartment.description }}</p>
            <p>{{ apartment.address_info }}</p>

          </div>

        </div>

        <h5>Informazioni aggiuntive</h5>
        <div class="row row-cols-2 row-cols-md-3 mb-3">

          <div class="col my-2 ">
            <i class="fa-solid fa-building"></i>
            Tipologia: {{ apartment.type }}
          </div>
          <!-- <div class="col my-2">
            Descrizione: {{ apartment.description }}
          </div> -->
          <div class="col my-2">
            <i class="fa-solid fa-house"></i>
            Grandezza del locale: {{ apartment.apartment_size }} m²
          </div>
          <div class="col my-2">
            Prezzo: <b>{{ apartment.price }} €</b> a notte
          </div>
          <div class="col my-2">
            <i class="fa-solid fa-bed"></i>
            Numeri di letti: {{ apartment.n_of_bed }}
          </div>
          <div class="col my-2">
            <i class="fa-solid fa-bath"></i>
            Numeri di bagni: {{ apartment.n_of_bathroom }}
          </div>
          <div class="col my-2">
            <i class="fa-solid fa-door-open"></i>
            Camere: {{ apartment.n_of_room }}
          </div>
        </div>

        <h5>Servizi offerti</h5>
        <span v-for="(service, index) in apartment.services" :key="index" class="badge mx-1">{{ service.name }}</span>

      </div>


      <!-- FORM CONTATTO HOST  -->

      <form v-if="!success" class="row" @submit.prevent="sendMail()">
        <h2>Invia un messaggio all'host</h2>

        <div class="col col-12 col-md-4 mb-3">
          <label for="author_first_name" class="form-label">Nome</label>
          <input v-model.trim="author_first_name" type="text" class="form-control" :class="{'is-invalid' : errors.first_name }" id="author_first_name" placeholder="Inserisci il nome">
          <p v-for="(error,index) in errors.author_first_name" :key="index" class="text-danger">{{ error }}</p>
        </div>

        <div class="col col-12 col-md-4 mb-3">
          <label for="author_last_name" class="form-label">Cognome</label>
          <input v-model.trim="author_last_name" type="text" class="form-control" :class="{'is-invalid' : errors.author_last_name }" id="author_last_name" placeholder="Inserisci il cognome">
          <p v-for="(error,index) in errors.author_last_name" :key="index" class="text-danger">{{ error }}</p>
        </div>

        <div class="col col-12 col-md-4 mb-3">
          <label for="author_email" class="form-label">Indirizzo email</label>
          <input v-model.trim="author_email" type="email" class="form-control" :class="{'is-invalid' : errors.author_email }" id="author_email" placeholder="Inserisci la tua email">
          <p v-for="(error,index) in errors.author_email" :key="index" class="text-danger">{{ error }}</p>
        </div>

        <div class="col col-12 mb-3">
          <label for="object" class="form-label">Oggetto del messaggio</label>
          <input v-model.trim="object" type="text" class="form-control" :class="{'is-invalid' : errors.author_email }" id="object" placeholder="Oggetto del messaggio">
          <p v-for="(error,index) in errors.object" :key="index" class="text-danger">{{ error }}</p>
        </div>

        <div class="mb-3">
          <label for="text" class="form-label">Messaggio</label>
          <textarea v-model.trim="text" class="form-control" :class="{'is-invalid' : errors.text }" placeholder="Inserisci un messaggio" id="text" rows="3"></textarea>
          <p v-for="(error,index) in errors.text" :key="index" class="text-danger">{{ error }}</p>
        </div>

        <div class="col-auto">
          <button type="submit" :disabled="sending" class="btn  mb-3">{{ sending ? 'Invio in corso' : 'Invia messaggio' }}</button>
        </div>
      </form>

      <div v-else class="p-4 shadow row">

        <div class="col-9">
          <h2>Il messaggio è stato inviato correttamente!</h2>
        </div>

        <div class="col-3  text-center d-flex justify-content-center align-items-center ">
          <router-link :to="{name: 'advancedSearch' }" class="h-100 text-center input-group-text " style="text-decoration: none;" >

              torna pagina di ricerca

          </router-link>
        </div>

      </div>

    </div>
</template>

<style lang="scss" scoped>
@use '../../scss/var' as *;

.apartment_top{
  display: flex;
  flex-direction: column;
}

.mapping{
  margin-top: 20px;
  width: 100%;
  height: 150px;
}

img{
    display: inline-block;
    object-fit: cover;
    width: 100%;
    height: auto;
}

h5{
  color: $brand-main;
  font-weight: bold;
}

span.badge{
  background-color: $brand-blue;
}

.input-group-text{
  background-color: $brand-main;
}
.btn{
  background-color: $brand-main;
}

@media screen and (min-width: 768px) {
  .apartment_top{
  display: flex;
  justify-content: space-between;
  flex-direction: row;
  height: 400px;
}
img{
    display: inline-block;
    object-fit: cover;
    width: 60%;
    height: auto;
}
.mapping{
  margin-top: 0px;
  width: 38%;
  height: 100%;
}
}
@media screen and (min-width: 992px) {

}
@media screen and (min-width: 1200px) {
  img{
    display: inline-block;
    object-fit: cover;
    width: 68%;
    height: auto;
}
.mapping{
  margin-top: 0px;
  width: 30%;
  height: 100%;
}

}
</style>
