package com.dgv6.app.api

import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import com.dgv6.app.util.Constants

object RetrofitClient {
    private var retrofit: Retrofit? = null

    fun getClient(): VtuApiService {
        if (retrofit == null) {
            retrofit = Retrofit.Builder()
                .baseUrl(Constants.BASE_URL)
                .addConverterFactory(GsonConverterFactory.create())
                .build()
        }
        return retrofit!!.create(VtuApiService::class.java)
    }
}
