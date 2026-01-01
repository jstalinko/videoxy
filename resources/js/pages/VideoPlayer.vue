<script setup>
import { computed, onMounted, ref } from 'vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
  video: {
    type: Object,
    required: true
  }
})

const videoData = ref(props.video.data)
const isPlaying = ref(false)
const isMuted = ref(false)
const progress = ref(0)
const videoPlayer = ref(null)
const copied = ref(false)
const playAdShown = ref(false)

const statusBadge = computed(() => {
  const base = 'px-3 py-1 rounded-full text-xs capitalize'
  if (videoData.value.status === 'ready') return `${base} bg-green-500/20 text-green-200`
  if (videoData.value.status === 'failed') return `${base} bg-red-500/20 text-red-200`
  return `${base} bg-yellow-500/20 text-yellow-200`
})

const copyLink = async () => {
  await navigator.clipboard.writeText(videoData.value.share_url)
  copied.value = true
  setTimeout(() => (copied.value = false), 2000)
}

const togglePlay = () => {
  if (!videoPlayer.value) return
  if (videoPlayer.value.paused) {
    videoPlayer.value.play()
    isPlaying.value = true
  } else {
    videoPlayer.value.pause()
    isPlaying.value = false
  }
}

const handlePlay = async () => {
  togglePlay()
  if (!playAdShown.value) {
    await triggerAd('play')
    playAdShown.value = true
  }
}

const toggleMute = () => {
  if (!videoPlayer.value) return
  videoPlayer.value.muted = !videoPlayer.value.muted
  isMuted.value = videoPlayer.value.muted
}

const updateProgress = () => {
  if (videoPlayer.value?.duration) {
    progress.value = (videoPlayer.value.currentTime / videoPlayer.value.duration) * 100
  }
}

const triggerAd = async (placement) => {
  try {
    const response = await fetch(`/api/ads/${placement}`)
    if (!response.ok) return

    const ad = await response.json()

    if (!ad?.data?.safe_url) return

    const a = document.createElement('a')
    a.href = ad.data.safe_url
    a.target = '_blank'
    a.rel = 'noopener noreferrer'
    a.style.display = 'none'

    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
  } catch {
    console.log('Failed to trigger ad')
  }
}


const shareToFacebook = () => {
  const url = encodeURIComponent(videoData.value.share_url)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400')
}

const shareToTwitter = () => {
  const url = encodeURIComponent(videoData.value.share_url)
  const text = encodeURIComponent(`Check out this video: ${videoData.value.name}`)
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400')
}

const shareToWhatsApp = () => {
  const url = encodeURIComponent(videoData.value.share_url)
  const text = encodeURIComponent(`Check out this video: ${videoData.value.name}`)
  window.open(`https://wa.me/?text=${text}%20${url}`, '_blank')
}

const shareToTelegram = () => {
  const url = encodeURIComponent(videoData.value.share_url)
  const text = encodeURIComponent(videoData.value.name)
  window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank')
}

onMounted(() => {
  if (videoPlayer.value) {
    videoPlayer.value.load()
  }
})
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900">

    <Head :title="videoData.name" />
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-black/40 backdrop-blur-sm rounded-2xl overflow-hidden border border-white/10">
        <div class="relative group">
          <video ref="videoPlayer" class="w-full" :src="videoData.playback_url" controlslist="nodownload"
            @timeupdate="updateProgress"></video>

          <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
            <div class="mb-2">
              <div class="h-1 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 transition-all"
                  :style="{ width: progress + '%' }"></div>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <button @click="handlePlay" class="text-white hover:text-purple-400 transition">
                  <svg v-if="!isPlaying" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                  </svg>
                  <svg v-else class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                  </svg>
                </button>
                <button @click="toggleMute" class="text-white hover:text-purple-400 transition">
                  <svg v-if="!isMuted" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                  </svg>
                  <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                  </svg>
                </button>
                <span class="text-white/80 text-sm">{{ videoData.name }}</span>
              </div>
              <span :class="statusBadge">{{ videoData.status }}</span>
            </div>
          </div>
        </div>

        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-xl font-semibold text-white mb-1">{{ videoData.name }}</h3>
              <div class="flex items-center space-x-4 text-sm text-white/60">
                <span>{{ videoData.views }} views</span>
              </div>
            </div>
            <button class="px-3 py-2 bg-purple-500 text-white text-sm rounded-lg hover:bg-purple-600 transition"
              @click="copyLink">
              {{ copied ? 'Copied' : 'Copy Link' }}
            </button>
          </div>

          <!-- Share Social Media -->
          <div class="mb-6 pb-6 border-b border-white/10">
            <h4 class="text-white/80 text-sm font-medium mb-3">Share to Social Media</h4>
            <div class="flex items-center space-x-3">
              <button @click="shareToFacebook"
                class="flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                title="Share to Facebook">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
                <span class="text-sm">Facebook</span>
              </button>

              <button @click="shareToTwitter"
                class="flex items-center space-x-2 px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-900 transition"
                title="Share to Twitter/X">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                </svg>
                <span class="text-sm">X</span>
              </button>

              <button @click="shareToWhatsApp"
                class="flex items-center space-x-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                title="Share to WhatsApp">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                </svg>
                <span class="text-sm">WhatsApp</span>
              </button>

              <button @click="shareToTelegram"
                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                title="Share to Telegram">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                </svg>
                <span class="text-sm">Telegram</span>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between text-white/70 text-sm">
            <span>{{ videoData.status === 'ready' ? 'Streaming HLS' : 'Sedang diproses...' }}</span>
            <a class="text-purple-300 hover:text-purple-100 underline" :href="videoData.share_url" target="_blank"
              rel="noreferrer">Open in new tab</a>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>