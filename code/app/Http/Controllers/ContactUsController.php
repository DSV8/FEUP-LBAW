<?php
 
namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;

class ContactUsController extends Controller
{
    
    /**
     * Shows all contact us instances in the admin dashboard.
     */
    public function showAll()
    {
        // Get all contact us instances ordered by date.
        $contactUsRequests = ContactUs::orderByDesc('date')->get();

        // Use the pages.admin_dashboard template to display all contact us requests.
        return view('pages.admin_dashboard', [
            'contactUsRequests' => $contactUsRequests
        ]);
    }

    /**
     * Creates a new contact us message.
     */
    public function create(Request $request)
    {
        // Create a new contact us instance.
        $contactUs = new ContactUs();

        // Validate the request data.
        $validatedData = $request->validate([
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'email|required|max:300',
            'message' => 'required|max:1000',
        ]);

        // Set contact us details.
        $contactUs->first_name = $request->input('first_name');
        $contactUs->last_name = $request->input('last_name');
        $contactUs->email = $request->input('email');
        $contactUs->message = $request->input('message');
        $contactUs->date = Carbon::now();

        // Save the contact us message.
        $contactUs->save();

        // Redirect to the home page with a success message.
        return redirect()->route('home')->with('success', 'Message sent successfully');
    }
}
?>