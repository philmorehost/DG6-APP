package com.dgv6.app.ui.dashboard

import android.os.Bundle
import android.view.View
import androidx.fragment.app.Fragment
import com.dgv6.app.R
import com.dgv6.app.databinding.FragmentProfileBinding

class ProfileFragment : Fragment(R.layout.fragment_profile) {
    private var _binding: FragmentProfileBinding? = null
    private val binding get() = _binding!!

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        _binding = FragmentProfileBinding.bind(view)

        setupProfile()
    }

    private fun setupProfile() {
        // Display user info, API key, and handle logout/security PIN
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
