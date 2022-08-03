<template>
	<div class="w-100 d-flex align-items-center justify-content-center">
		<div class="card card-sm bg-dark text-white">
			<div class="card-body p-5">
				<slot />
			</div>
		</div>
	</div>
</template>

<script>
export default {

};
</script>

<style>
#app>div {
	background-color: var(--bg-color-1) !important;
}

.card-body {
	background-color: var(--bg-color-2) !important;
}

.card-body input {
	background-color: #1F2129 !important;
	border: 0;
}
</style>