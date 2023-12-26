<script setup>
import { onMounted, reactive, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import mercuryImg from '../../images/planets/mercury.png';
import venusImg from '../../images/planets/venus.png';
import earthImg from '../../images/planets/earth.png';
import marsImg from '../../images/planets/mars.png';
import jupiterImg from '../../images/planets/jupiter.png';
import saturnImg from '../../images/planets/saturn.png';
import uranusImg from '../../images/planets/uranus.png';
import neptuneImg from '../../images/planets/neptune.png';
import defaultImg from '../../images/planets/default.png';
import PlanetImage from '@/Components/PlanetImage.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import logo1 from '../../images/logos/090045.png';
import logo2 from '../../images/logos/090131.png';
import logo3 from '../../images/logos/090218.png';
import logo4 from '../../images/logos/090303.png';
import logo5 from '../../images/logos/090444.png';
import logo6 from '../../images/logos/090556.png';
import logo7 from '../../images/logos/090721.png';
import logo8 from '../../images/logos/090821.png';
import logo9 from '../../images/logos/090903.png';
import logo10 from '../../images/logos/091117.png';
import logo11 from '../../images/logos/091337.png';
import logo12 from '../../images/logos/091428.png';
import logo13 from '../../images/logos/091511.png';
import logo14 from '../../images/logos/091555.png';
import logo15 from '../../images/logos/093701.png';
import logo16 from '../../images/logos/094900.png';
import logo17 from '../../images/logos/094948.png';
import logo18 from '../../images/logos/095217.png';
import logo19 from '../../images/logos/095306.png';
import logo20 from '../../images/logos/095411.png';
import logo21 from '../../images/logos/095651.png';
import logo22 from '../../images/logos/095947.png';
import logo23 from '../../images/logos/101111.png';

const toast            = useToast();
const planetImages     = {
    default: defaultImg,
    mercury: mercuryImg,
    venus:   venusImg,
    earth:   earthImg,
    mars:    marsImg,
    jupiter: jupiterImg,
    saturn:  saturnImg,
    uranus:  uranusImg,
    neptune: neptuneImg,
};
const logos            = [
    logo1,
    logo2,
    logo3,
    logo4,
    logo5,
    logo6,
    logo7,
    logo8,
    logo9,
    logo10,
    logo11,
    logo12,
    logo13,
    logo14,
    logo15,
    logo16,
    logo17,
    logo18,
    logo19,
    logo20,
    logo21,
    logo22,
    logo23,
];
const cardTitles       = [
    'The best trip of your life',
    'The trip you\'ve always dreamed of',
    'The trip you\'ve always wanted',
    'The trip you\'ve always needed',
    'The trip you\'ve always desired',
    'The trip you\'ve always wished for',
    'The trip you\'ve always hoped for',
    'The trip you\'ve always craved',
    'The trip you\'ve always yearned for',
    'The trip you\'ve always longed for',
    'The trip you\'ve always wanted',
    'Get away from it all',
    'Get lost in the beauty of the stars',
    'Get lost in the beauty of the universe',
    'Get lost in the beauty of the cosmos',
    'Get lost in the beauty of the galaxy',
    'Get lost in the beauty of the milky way',
    'Get lost in the beauty of the solar system',
    'Get lost in the beauty of the planets',
    'Spend some time away from home',
    'Spend some time away from earth',
    'Quality time with your loved ones',
    'Quality time with your family',
    'Quality time with your friends',
    'Quality time with your partner',
];
const tags             = [
    'photography',
    'travel',
    'vacation',
    'holiday',
    'adventure',
    'explore',
    'scenery',
    'nature',
    'landscape',
    'solar',
    'sail',
    'space',
    'stars',
    'galaxy',
    'cosmos',
    'universe',
    'milky way',
    'planets',
];
const planets          = ref([]);
const travelRoutes     = ref([]);
const filteredRoutes   = ref([]);
const destinations     = ref([]);
const from             = ref(null);
const to               = ref(null);
const companyFilter    = ref('all');
const priceSort        = ref('none');
const distanceSort     = ref('none');
const travelTimeSort   = ref('none');
const departureSort    = ref('none');
const arrivalSort      = ref('none');
const selectedRoute    = ref(null);
const priceListUuid    = ref(null);
const showBookingModal = ref(false);
const bookingForm      = reactive({ first_name: '', last_name: '' });
const boxClasses       = 'w-full mx-auto mt-8 pt-12 pb-8 border-2 dark:border-gray-600 border-gray-300 rounded-3xl';
onMounted(async () => {
    const data    = await axios.get('/planets');
    planets.value = data.data.data;
});
watch([from, to], async () => {
    if (from.value && to.value) {
        await fetchRoutes();
    }
});
watch(companyFilter, () => {
    if (companyFilter.value === 'all') {
        filteredRoutes.value = travelRoutes.value;
        return;
    }
    filteredRoutes.value = travelRoutes.value.filter((route) => {
        return route.company_name === companyFilter.value;
    });
});
watch(priceSort, () => {
    if (priceSort.value === 'none') {
        return;
    }
    if (priceSort.value === 'asc') {
        sortProvidersByPrice('asc');
        return;
    }
    if (priceSort.value === 'desc') {
        sortProvidersByPrice('desc');
        return;
    }
});
watch(distanceSort, () => {
    if (distanceSort.value === 'none') {
        return;
    }
    if (distanceSort.value === 'asc') {
        sortProvidersByDistance('asc');
        return;
    }
    if (distanceSort.value === 'desc') {
        sortProvidersByDistance('desc');
        return;
    }
});
watch(travelTimeSort, () => {
    if (travelTimeSort.value === 'none') {
        return;
    }
    if (travelTimeSort.value === 'asc') {
        sortProvidersByTravelTime('asc');
        return;
    }
    if (travelTimeSort.value === 'desc') {
        sortProvidersByTravelTime('desc');
        return;
    }
});
watch(departureSort, () => {
    if (departureSort.value === 'none') {
        return;
    }
    if (departureSort.value === 'asc') {
        sortProvidersByDeparture('asc');
        return;
    }
    if (departureSort.value === 'desc') {
        sortProvidersByDeparture('desc');
        return;
    }
});
watch(arrivalSort, () => {
    if (arrivalSort.value === 'none') {
        return;
    }
    if (arrivalSort.value === 'asc') {
        sortProvidersByArrival('asc');
        return;
    }
    if (arrivalSort.value === 'desc') {
        sortProvidersByArrival('desc');
        return;
    }
});
const fetchRoutes               = async () => {
    const res = axios.get(`/routes/${ from.value.uuid }/${ to.value.uuid }`);
    res.then((response) => {
        priceListUuid.value  = response.data.price_list_uuid;
        travelRoutes.value   = response.data.routes;
        filteredRoutes.value = response.data.routes;
        if (travelRoutes.value.length === 0) {
            toast(
                'No possible travel routes found. Currently no company offers a route with matching dates and/or destinations',
                {
                    type:                   'info',
                    position:               'top-right',
                    timeout:                5000,
                    closeOnClick:           true,
                    pauseOnFocusLoss:       true,
                    pauseOnHover:           true,
                    showCloseButtonOnHover: false,
                    closeButton:            'button',
                },
            );
        } else {
            toast('Travel routes found! Scroll down to book your Dream Getaway(tm) Now!!!', {
                type:                   'success',
                position:               'top-right',
                timeout:                5000,
                closeOnClick:           true,
                pauseOnFocusLoss:       true,
                pauseOnHover:           true,
                showCloseButtonOnHover: false,
                closeButton:            'button',
            });
        }
    });
};
const handleFromChange          = async (planet) => {
    destinations.value   = [];
    travelRoutes.value   = [];
    filteredRoutes.value = [];
    resetFilters();
    from.value = planet;
    to.value   = null;
};
const handleToChange            = async (planet) => {
    if (planet === from.value) {
        toast('You cannot select the same planet as destination as your departure planet', {
            type:                   'error',
            position:               'top-right',
            timeout:                5000,
            closeOnClick:           true,
            pauseOnFocusLoss:       true,
            pauseOnHover:           true,
            showCloseButtonOnHover: false,
            closeButton:            'button',
        });
        return;
    }
    travelRoutes.value   = [];
    filteredRoutes.value = [];
    resetFilters();
    to.value = planet;
};
const handleBooking             = async () => {
    const res = axios.post(`/bookings`, {
        price_list_uuid: priceListUuid.value,
        first_name:      bookingForm.first_name,
        last_name:       bookingForm.last_name,
        company_uuid:    selectedRoute.value.company_uuid,
        legs:            selectedRoute.value.legs,
        distance:        selectedRoute.value.distance,
        travel_time:     selectedRoute.value.travel_time,
        departure:       selectedRoute.value.departure,
        arrival:         selectedRoute.value.arrival,
        price:           selectedRoute.value.price,
    });
    res.then(() => {
        closeBookingModal();
        handleFromChange(null);
        toast('Booking created successfully', {
            type:                   'success',
            position:               'top-right',
            timeout:                5000,
            closeOnClick:           true,
            pauseOnFocusLoss:       true,
            pauseOnHover:           true,
            showCloseButtonOnHover: false,
            closeButton:            'button',
        });
    }).catch((error) => {
        toast('Error creating booking. ' + error.response.data.message, {
            type:                   'error',
            position:               'top-right',
            timeout:                5000,
            closeOnClick:           true,
            pauseOnFocusLoss:       true,
            pauseOnHover:           true,
            showCloseButtonOnHover: false,
            closeButton:            'button',
        });
    });
};
const resetFilters              = () => {
    companyFilter.value  = 'all';
    priceSort.value      = 'none';
    distanceSort.value   = 'none';
    travelTimeSort.value = 'none';
    departureSort.value  = 'none';
    arrivalSort.value    = 'none';
};
const openBookingModal          = (route) => {
    selectedRoute.value    = route;
    showBookingModal.value = true;
};
const closeBookingModal         = () => {
    selectedRoute.value    = null;
    showBookingModal.value = false;
};
const randomInspiringCardTitle  = () => {
    return cardTitles[Math.floor(Math.random() * cardTitles.length)];
};
const getPlanetImage            = (planetName) => {
    let src = planetImages[planetName.toLowerCase()];
    if (!src) {
        src = planetImages.default;
    }
    return src;
};
const formatDistance            = (distance) => {
    return distance.toLocaleString('et-ET', { maximumFractionDigits: 2 }) + ' km';
};
const formatDuration            = (time) => {
    const hours   = Math.floor(time / 60);
    const minutes = time % 60;
    return `${ hours }h ${ minutes }m`;
};
const formatDateTime            = (date) => {
    return new Date(date).toLocaleString('et-ET', {
        year:   'numeric',
        month:  'numeric',
        day:    'numeric',
        hour:   'numeric',
        minute: 'numeric',
    });
};
const formatCurrency            = (currency) => {
    // format price to readable format with currency symbol and thousands separator
    return new Intl.NumberFormat('et-ET', { style: 'currency', currency: 'EUR' }).format(currency);
};
const sortProvidersByPrice      = (dir) => {
    const routes = travelRoutes.value;
    if (dir === 'asc') {
        routes.sort((a, b) => {
            return a.price - b.price;
        });
        travelRoutes.value = routes;
        return;
    }
    if (dir === 'desc') {
        routes.sort((a, b) => {
            return b.price - a.price;
        });
        travelRoutes.value = routes;
        return;
    }
};
const sortProvidersByDistance   = (dir) => {
    const routes = travelRoutes.value;
    if (dir === 'asc') {
        routes.sort((a, b) => {
            return a.distance - b.distance;
        });
        travelRoutes.value = routes;
        return;
    }
    if (dir === 'desc') {
        routes.sort((a, b) => {
            return b.distance - a.distance;
        });
        travelRoutes.value = routes;
        return;
    }
};
const sortProvidersByTravelTime = (dir) => {
    const routes = travelRoutes.value;
    if (dir === 'asc') {
        routes.sort((a, b) => {
            return a.travel_time - b.travel_time;
        });
        travelRoutes.value = routes;
        return;
    }
    if (dir === 'desc') {
        routes.sort((a, b) => {
            return b.travel_time - a.travel_time;
        });
        travelRoutes.value = routes;
        return;
    }
};
const sortProvidersByDeparture  = (dir) => {
    const routes = travelRoutes.value;
    routes.every((r, i) => r.start_ts = new Date(r.departure).getTime());
    if (dir === 'asc') {
        routes.sort((a, b) => {
            return a.start_ts - b.start_ts;
        });
        travelRoutes.value = routes;
        return;
    }
    if (dir === 'desc') {
        routes.sort((a, b) => {
            return b.start_ts - a.start_ts;
        });
        travelRoutes.value = routes;
        return;
    }
};
const sortProvidersByArrival    = (dir) => {
    const routes = travelRoutes.value;
    routes.every((r, i) => r.end_ts = new Date(r.arrival).getTime());
    if (dir === 'asc') {
        routes.sort((a, b) => {
            return a.end_ts - b.end_ts;
        });
        travelRoutes.value = routes;
        return;
    }
    if (dir === 'desc') {
        routes.sort((a, b) => {
            return b.end_ts - a.end_ts;
        });
        travelRoutes.value = routes;
        return;
    }
};
</script>

<template>
    <div class="w-full max-w-7xl bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-20 mt-28">

        <h1 class="w-full text-center pb-12">Book your dream vacation on our Solar Sailers!</h1>

        <Transition name="fade">
            <div v-if="!from || from.value === null" :class="boxClasses">
                <h3 class="w-full text-center pb-12">Select your departure planet below</h3>
                <div v-if="planets.length > 0" class="w-full flex flex-wrap mx-auto justify-center">
                    <div v-for="(source, idx) in planets"
                         :key="idx"
                         class="grid-cols-1 mx-1 sm:mx-2 md:mx-4"
                         @click="handleFromChange(source)">
                        <PlanetImage :src="getPlanetImage(source.name)" :alt="source.name"/>
                        <p>{{ source.name }}</p>
                    </div>
                </div>
            </div>
            <div v-else :class="boxClasses">
                <h3 class="w-full text-center pb-12">Departing from: <span class="text-blue-500">{{ from.name }}</span>
                </h3>
                <div :class="{'opacity-1': from}" class="w-full flex mx-auto justify-center">
                    <PlanetImage :src="getPlanetImage(from.name)" :alt="from.name"
                                 classes="cursor-auto border-blue-400"/>
                </div>
                <div class="w-full mx-auto flex justify-center mt-10">
                    <PrimaryButton @click="handleFromChange(null)">Reset Departure</PrimaryButton>
                </div>
            </div>
        </Transition>

        <Transition name="fade">
            <div v-if="planets.length > 0 && from && from.value !== null" :class="boxClasses">
                <h3 class="w-full text-center pb-12">Select your destination planet below</h3>
                <div v-if="!to || to.value === null">
                    <div class="w-full flex flex-wrap mx-auto justify-center">
                        <div v-for="(destination, idx) in planets"
                             :key="idx"
                             class="grid-cols-1 mx-1 sm:mx-2 md:mx-4"
                             @click="handleToChange(destination)">
                            <PlanetImage :src="getPlanetImage(destination.name)" :alt="destination.name"/>
                            <p>{{ destination.name }}</p>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <h3 class="w-full text-center pb-12">Destination: <span class="text-blue-500">{{ to.name }}</span>
                    </h3>
                    <div :class="{'opacity-1': from}" class="w-full flex mx-auto justify-center">
                        <PlanetImage :src="getPlanetImage(to.name)" :alt="to.name"
                                     classes="cursor-auto border-blue-400"/>
                    </div>
                    <div class="w-full mx-auto flex justify-center mt-10">
                        <PrimaryButton @click="handleToChange(null)">Reset Destination</PrimaryButton>
                    </div>
                </div>
            </div>
        </Transition>

        <Transition name="fade">
            <div v-if="filteredRoutes.length > 0" class="flex flex-col justify-center">
                <h3 class="w-full text-center mt-12 text-blue-600">Available routes</h3>
                <div class="mx-auto mt-6">
                    <PrimaryButton @click="resetFilters">Reset Filters</PrimaryButton>
                </div>
                <div class="filters bg-gray-400 dark:bg-gray-700 w-full flex flex-row flex-wrap justify-center my-12
                            p-8 rounded-md">
                    <select v-model="companyFilter"
                            class="bg-gray-200 dark:bg-gray-800">
                        <option value="all" selected>All</option>
                        <option v-for="(route, idx) in travelRoutes" :key="idx" :value="route.company_name">
                            {{ route.company_name }}
                        </option>
                    </select>
                    <select v-model="priceSort"
                            class="bg-gray-200 dark:bg-gray-800">
                        <option value="none" selected>Price</option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                    <select v-model="distanceSort"
                            class="bg-gray-200 dark:bg-gray-800">
                        <option value="none" selected>Distance</option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                    <select v-model="travelTimeSort"
                            class="bg-gray-200 dark:bg-gray-800">
                        <option value="none" selected>Travel Time</option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                    <select v-model="departureSort"
                            class="bg-gray-200 dark:bg-gray-800">
                        <option value="none" selected>Departure</option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                    <select v-model="arrivalSort"
                            class="bg-gray-200 dark:bg-gray-800">
                        <option value="none" selected>Arrival</option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                </div>
                <div class="routes w-full flex flex-row flex-wrap justify-center mx-auto my-8">
                    <div class="max-w-sm rounded overflow-hidden shadow-lg mx-6 my-4"
                         v-for="(route, idx) in filteredRoutes" :key="idx">
                        <img class="w-full" :src="logos[Math.floor(Math.random() * logos.length)]" alt="Agency logo">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">
                                <p>
                                    {{ randomInspiringCardTitle() }}, provided by <span
                                        class="text-blue-500">{{ route.company_name }}</span>
                                </p>
                            </div>
                            <div v-for="(leg, idx) in route.legs" :key="idx" class="w-full flex flex-col">
                                <span v-if="idx !== route.legs.length - 1">
                                    <span>{{ leg.from_name }}</span>
                                    <span> -> </span>
                                    <span>{{ leg.to_name }}</span>
                                    <span class="text-gray-500" v-if="idx !== 0 && idx !== route.legs.length">
                                        - Layover, with duration of {{ formatDuration(leg.travel_time) }}
                                    </span>
                                </span>
                                <span v-else>
                                    <span>{{ leg.from_name }}</span>
                                    <span> -> </span>
                                    <span>{{ leg.to_name }}</span>
                                    
                                </span>
                            </div>
                            <hr class="my-2">
                            <p>Launch at {{ route.departure }}</p>
                            <p>Arrive at {{ route.arrival }}</p>
                            <p>Total luxurious time spent: {{ formatDuration(route.travel_time) }}</p>
                            <p>Total cosmic distance: {{ formatDistance(route.distance) }}</p>
                            <p>Only: {{ formatCurrency(route.price) }}</p>
                        </div>
                        <div class="px-6 pt-4 pb-2">
                            <span v-for="(i, idx) in Math.floor(Math.random() * 3) + 1" :key="idx"
                                  class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                                #{{ tags[Math.floor(Math.random() * tags.length)] }}
                            </span>
                        </div>
                        <div class="w-full py-4 px-6 mx-auto">
                            <PrimaryButton @click="openBookingModal(route)">Book Now!!!</PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <DialogModal :show="showBookingModal" @close="closeBookingModal">
            <template #title>
                <h3 class="w-full text-center pb-12">Book your trip</h3>
            </template>

            <template #content>
                <p class="text-center">
                    You are about to book a trip from
                    <span class="text-blue-500">{{ from.name }}</span> to
                    <span class="text-blue-500">{{ to.name }}</span> with
                    <span class="text-blue-500">{{ selectedRoute.company_name }}</span> for
                    <span class="text-blue-500">{{ formatCurrency(selectedRoute.price) }}</span>.
                </p>
                <p>
                    The trip will take
                    <span class="text-blue-500">{{ formatDuration(selectedRoute.travel_time) }}</span> and will start on
                    <span class="text-blue-500">{{ formatDateTime(selectedRoute.departure) }}</span> and end on
                    <span class="text-blue-500">{{ formatDateTime(selectedRoute.arrival) }}</span>.
                </p>

                <p class="text-center">Please enter your name below to confirm the booking.</p>

                <div class="w-full">
                    <div class="flex flex-wrap my-6 pt-2">
                        <div class="w-full p-0 flex justify-center flex-row items-center">
                            <label for="firstName" class="text-gray-800 dark:text-gray-300 mr-12">First Name</label>
                            <input id="firstName"
                                   type="text"
                                   v-model.trim="bookingForm.first_name"
                                   class="p-1 border-2 border-gray-600 bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <div class="flex flex-wrap my-6 pt-2">
                        <div class="w-full p-0 flex justify-center flex-row items-center">
                            <label for="lastName" class="text-gray-800 dark:text-gray-300 mr-12">Last Name</label>
                            <input id="lastName"
                                   type="text"
                                   v-model.trim="bookingForm.last_name"
                                   class="p-1 border-2 border-gray-600 bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                        </div>
                    </div>
                </div>
            </template>

            <template #footer>
                <PrimaryButton :disabled="!bookingForm.first_name || !bookingForm.last_name"
                               :class="{'cursor-auto dark:bg-gray-600 dark:hover:bg-gray-600 bg-gray-600 dark:text-gray-100 text-gray-100 hover:bg-gray-600 opacity-50 active:bg-gray-600 dark:active:bg-gray-600': !bookingForm.first_name || !bookingForm.last_name}"
                               @click="handleBooking">Book
                </PrimaryButton>
            </template>
        </DialogModal>
    </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
