package com.dgv6.app.viewmodel

import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.dgv6.app.api.VtuApiService
import com.dgv6.app.data.model.User
import kotlinx.coroutines.launch

class MainViewModel(private val apiService: VtuApiService) : ViewModel() {

    private val _user = MutableLiveData<User?>()
    val user: LiveData<User?> = _user

    fun fetchProfile(apiKey: String) {
        viewModelScope.launch {
            try {
                val response = apiService.getServices(mapOf("api_key" to apiKey))
                // Handle response and update _user
            } catch (e: Exception) {
                // Handle error
            }
        }
    }
}
