package com.dgv6.app.data.model

data class User(
    val username: String,
    val firstname: String,
    val lastname: String,
    val email: String,
    val phone: String,
    val balance: Double,
    val api_key: String,
    val account_level: Int,
    val level_name: String,
    val kyc_status: Int,
    val kyc_verified: String,
    val security_pin_set: Boolean
)

data class Transaction(
    val reference: String,
    val type: String,
    val amount: Double,
    val discounted_amount: Double,
    val balance_before: Double,
    val balance_after: Double,
    val description: String,
    val status: Int,
    val status_name: String,
    val date: String
)
