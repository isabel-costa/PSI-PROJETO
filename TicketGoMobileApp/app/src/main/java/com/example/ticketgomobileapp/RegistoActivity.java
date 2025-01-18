package com.example.ticketgomobileapp;

import android.app.DatePickerDialog;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.material.textfield.TextInputEditText;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;

public class RegistoActivity extends AppCompatActivity {

    private static final String REGISTER_URL = "https://localhost/SIS/TicketGo/backend/web/api/auth/signup"; // Replace with your API endpoint

    private TextInputEditText nameEditText;
    private TextInputEditText usernameEditText;
    private TextInputEditText passwordEditText;
    private TextInputEditText confirmPasswordEditText;
    private TextInputEditText dataNascimentoEditText;
    private TextInputEditText nifEditText;
    private TextInputEditText moradaEditText;
    private Button registerButton;
    private Button dataNascimentoPickerButton;
    private TextView loginTextView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_registo);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        // Inicializar as views
        nameEditText = findViewById(R.id.nameEditText);
        usernameEditText = findViewById(R.id.usernameEditText);
        passwordEditText = findViewById(R.id.passwordEditText);
        confirmPasswordEditText = findViewById(R.id.confirmPasswordEditText);
        dataNascimentoEditText = findViewById(R.id.dataNascimentoEditText);
        nifEditText = findViewById(R.id.nifEditText);
        moradaEditText = findViewById(R.id.moradaEditText);
        registerButton = findViewById(R.id.registerButton);
        dataNascimentoPickerButton = findViewById(R.id.dataNascimentoPickerButton);
        loginTextView = findViewById(R.id.loginTextView);

        // Configurar o DatePickerDialog
        dataNascimentoPickerButton.setOnClickListener(v -> {
            Calendar calendar = Calendar.getInstance();
            int year = calendar.get(Calendar.YEAR);
            int month = calendar.get(Calendar.MONTH);
            int day = calendar.get(Calendar.DAY_OF_MONTH);

            DatePickerDialog datePickerDialog = new DatePickerDialog(
                    RegistoActivity.this,
                    (view, year1, month1, dayOfMonth) -> {
                        String selectedDate = dayOfMonth + "/" + (month1 + 1) + "/" + year1;
                        dataNascimentoEditText.setText(selectedDate);
                    },
                    year, month, day);
            datePickerDialog.show();
        });

        // Definir click listener para o botão de registo
        registerButton.setOnClickListener(v -> {
            String nome = nameEditText.getText().toString().trim();
            String username = usernameEditText.getText().toString().trim();
            String password = passwordEditText.getText().toString().trim();
            String confirmPassword = confirmPasswordEditText.getText().toString().trim();
            String dataNascimento = dataNascimentoEditText.getText().toString().trim();
            String nif = nifEditText.getText().toString().trim();
            String morada = moradaEditText.getText().toString().trim();

            if (nome.isEmpty() || username.isEmpty() || password.isEmpty() || confirmPassword.isEmpty() ||
                    dataNascimento.isEmpty() || nif.isEmpty() || morada.isEmpty()) {
                Toast.makeText(RegistoActivity.this, "Por favor, preencha todos os campos.", Toast.LENGTH_SHORT).show();
            } else if (!password.equals(confirmPassword)) {
                Toast.makeText(RegistoActivity.this, "As palavras-passe não coincidem.", Toast.LENGTH_SHORT).show();
            } else {
                registerUser(nome, username, password, dataNascimento, nif, morada);
            }
        });

        loginTextView.setOnClickListener(v -> {
            Intent intent = new Intent(RegistoActivity.this, LoginActivity.class);
            startActivity(intent);
        });
    }

    private void registerUser(String nome, String username, String password, String dataNascimento, String nif, String morada) {
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, REGISTER_URL,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            // Analisar a resposta JSON
                            JSONObject jsonObject = new JSONObject(response);
                            boolean success = jsonObject.getBoolean("success");
                            String message = jsonObject.getString("message");

                            if (success) {
                                Toast.makeText(RegistoActivity.this, "Registo bem-sucedido!", Toast.LENGTH_SHORT).show();

                                // Navegar para a atividade de login
                                Intent intent = new Intent(RegistoActivity.this, LoginActivity.class);
                                startActivity(intent);
                                finish();
                            } else {
                                Toast.makeText(RegistoActivity.this, message, Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            Log.e("RegistoActivity", "JSON parsing error: " + e.getMessage());
                            Toast.makeText(RegistoActivity.this, "Erro ao processar a resposta do servidor.", Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e("RegistoActivity", "API call failed: " + error.getMessage());
                        Toast.makeText(RegistoActivity.this, "Falha na conexão. Verifique a sua internet.", Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                // Enviar os detalhes de registo como parâmetros do método POST
                Map<String, String> params = new HashMap<>();
                params.put("nome", nome);
                params.put("username", username);
                params.put("password", password);
                params.put("dataNascimento", dataNascimento);
                params.put("nif", nif);
                params.put("morada", morada);
                return params;
            }
        };

        // Adicionar o pedido à queue (fila)
        requestQueue.add(stringRequest);
    }
}