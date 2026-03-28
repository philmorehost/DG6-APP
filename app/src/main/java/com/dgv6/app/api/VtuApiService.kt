package com.dgv6.app.api

import retrofit2.Response
import retrofit2.http.*

interface VtuApiService {

    @POST("web/api/login.php")
    suspend fun login(@Body request: Map<String, String>): Response<Map<String, Any>>

    @POST("web/api/register.php")
    suspend fun register(@Body request: Map<String, String>): Response<Map<String, Any>>

    @POST("web/api/profile.php")
    suspend fun getProfile(@Body body: Map<String, String>): Response<Map<String, Any>>

    @POST("web/api/services.php")
    suspend fun getServices(@Body body: Map<String, String>): Response<Map<String, Any>>

    @POST("web/api/gift-card.php")
    suspend fun giftCardAction(@Body body: Map<String, Any>): Response<Map<String, Any>>

    @POST("web/api/virtual-card.php")
    suspend fun virtualCardAction(@Body body: Map<String, Any>): Response<Map<String, Any>>

    @POST("web/api/crypto.php")
    suspend fun cryptoAction(@Body body: Map<String, Any>): Response<Map<String, Any>>

    @POST("web/api/airtime.php")
    suspend fun purchaseAirtime(@Body body: Map<String, Any>): Response<Map<String, Any>>

    @POST("web/api/data.php")
    suspend fun purchaseData(@Body body: Map<String, Any>): Response<Map<String, Any>>

    @POST("web/api/transactions.php")
    suspend fun getTransactions(@Body body: Map<String, Any>): Response<Map<String, Any>>

    @POST("web/api/share-fund.php")
    suspend fun shareFund(@Body body: Map<String, Any>): Response<Map<String, Any>>

    @POST("web/api/withdrawal.php")
    suspend fun withdrawToBank(@Body body: Map<String, Any>): Response<Map<String, Any>>

    @GET("web/api/site-info.php")
    suspend fun getSiteInfo(): Response<Map<String, Any>>
}
