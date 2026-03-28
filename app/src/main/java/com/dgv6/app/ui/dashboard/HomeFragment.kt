package com.dgv6.app.ui.dashboard

import android.os.Bundle
import android.view.View
import androidx.fragment.app.Fragment
import com.dgv6.app.R
import com.dgv6.app.databinding.FragmentHomeBinding

class HomeFragment : Fragment(R.layout.fragment_home) {
    private var _binding: FragmentHomeBinding? = null
    private val binding get() = _binding!!

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        _binding = FragmentHomeBinding.bind(view)

        // Fetch profile data from ViewModel
        updateUI()
    }

    private fun updateUI() {
        // Logic to update balance and transactions
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
