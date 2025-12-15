<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@/types'

const props = defineProps<{ ads: Array<any> }>()

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Manage Ads', href: '/dashboard/ads' }
]
</script>

<template>
  <Head title="Manage Ads" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-4">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Manage Ads</h2>
        <span class="text-sm text-muted-foreground">Active: {{ props.ads.length }}</span>
      </div>
      <div class="overflow-x-auto rounded-lg border border-sidebar-border/70">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
          <thead class="bg-muted/40">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Placement</th>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Target</th>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Slug</th>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="ad in props.ads" :key="ad.id">
              <td class="px-4 py-3 text-sm">{{ ad.title }}</td>
              <td class="px-4 py-3 text-sm">{{ ad.placement }}</td>
              <td class="px-4 py-3 text-sm truncate max-w-xs">{{ ad.target_url }}</td>
              <td class="px-4 py-3 text-sm font-mono">{{ ad.slug }}</td>
              <td class="px-4 py-3 text-sm">
                <span :class="ad.is_active ? 'text-green-600 dark:text-green-300' : 'text-red-600 dark:text-red-300'">
                  {{ ad.is_active ? 'Active' : 'Disabled' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
