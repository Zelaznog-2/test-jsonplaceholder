<script setup>
// import
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps } from 'vue';
import Form from './partials/Form.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';


// props
const props = defineProps({
    user: Object | null
});


const form = useForm({
    id: 0,
    name: '',
    username: '',
    email: '',
    website: '',
    address : {
        street: '',
        suite: '',
        city: '',
        zipcode: '',
        geo: {
            lat: '',
            lng: ''
        }
    },
    phone: '',
    company: {
        name: '',
        catchPhrase: '',
        bs: ''
    },
    password: '',
    password_confirmation: '',
    isRedirect: false
})


// methods
const setData = () => {
    if (props.user) {
        form.id = props.user.id;
        form.name = props.user.name;
        form.username = props.user.username;
        form.email = props.user.email;
        form.website = props.user.website;
        form.address.street = props.user.address.street;
        form.address.suite = props.user.address.suite;
        form.address.city = props.user.address.city;
        form.address.zipcode = props.user.address.zipcode;
        form.address.geo.lat = props.user.address.geo.lat;
        form.address.geo.lng = props.user.address.geo.lng;
        form.phone = props.user.phone;
        form.company.name = props.user.company.name;
        form.company.catchPhrase = props.user.company.catchPhrase;
        form.company.bs = props.user.company.bs;

    }
}


const submit = () => {

    form.isRedirect = true;

    if (form.id === 0) {
        form.post(route('users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire(
            'Actualizado!',
            'El usuario ha sido creado.',
            'success'
            )
        },
        onError: () => {
            Swal.fire(
            'Error!',
            'EL usuario no ha sido creado.',
            'error'
            )
        }
        })
    } else {
        form.put(route('users.update', form.id), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire(
            'Actualizado!',
            'El usuario ha sido actualizado.',
            'success'
            )
        },
        onError: () => {
            Swal.fire(
            'Error!',
            'EL usuario no ha sido actualizado.',
            'error'
            )
        }
        })
    }
}

// onMounted
setData()

</script>

<template>
    <Head :title="`${form.id === 0 ? 'Nuevo' : 'Actualizar'} Usuario`" />

    <AuthenticatedLayout>
        <template #header>
        <h1>{{ form.id === 0 ? 'Nuevo' : 'Actualizar' }} Usuario</h1>
        </template>

        <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">

            <Form :form="form" @submit="submit()"/>
            </div>
        </div>
        </div>

    </AuthenticatedLayout>
</template>