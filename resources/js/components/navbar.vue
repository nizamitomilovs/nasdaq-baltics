<template>
    <nav class="nav">
        <a :href="route('dashboard')">Home</a>
        <div class="logout">
            <a :href="route('logout')">Log Out</a>
        </div>
    </nav>
</template>
<script>
export default {

}
</script>
