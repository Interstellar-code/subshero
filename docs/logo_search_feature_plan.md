# Logo Search Feature Implementation Plan

## Project Overview
This plan outlines the implementation of a logo search and download feature for the Subshero application. When a product name is entered in the admin product creation form, a search icon will appear next to it. Clicking this icon will search for a logo/icon for the product and download it to the application's storage where other product logos are stored.

## Current Implementation
- Products are created through the admin interface using a modal form
- Product logos are manually uploaded through the form
- Images are stored in "client/1/product/logos" for logos and "client/1/product/favicons" for favicons
- The File model handles image uploads and storage
- The ProductModel handles product data management

## Tasks

### 1. Backend API Development
- [ ] Create a new controller method for logo search API
- [ ] Implement logo search functionality using a third-party API (e.g., Clearbit Logo API, Google Custom Search API)
- [ ] Create an endpoint to fetch and download the logo
- [ ] Implement error handling and validation
- [ ] Store the downloaded logo in the appropriate directory

### 2. Frontend Implementation
- [ ] Modify the product creation form to add a search icon next to the product name field
- [ ] Add JavaScript functionality to show/hide the search icon based on product name input
- [ ] Implement click handler for the search icon to trigger the logo search
- [ ] Create a modal or dropdown to display search results
- [ ] Add functionality to select and download the chosen logo

### 3. Integration with Existing Code
- [ ] Integrate with the existing File model for storing downloaded images
- [ ] Update the ProductModel to associate the downloaded logo with the product
- [ ] Ensure compatibility with existing image upload functionality
- [ ] Handle cases where a logo is already uploaded

### 4. Testing and Validation
- [ ] Test the logo search functionality with various product names
- [ ] Validate the downloaded images meet the required dimensions and format
- [ ] Test error scenarios and edge cases
- [ ] Ensure the feature works seamlessly with the existing product creation flow

### 5. Documentation and Deployment
- [ ] Document the new feature and API endpoints
- [ ] Create usage guidelines for administrators
- [ ] Deploy the changes to the production environment
- [ ] Monitor for any issues post-deployment

## Technical Approach

### Logo Search API
We will implement a logo search API that uses web scraping to find logos.

> **IMPORTANT UPDATE**: The Clearbit Logo API will be sunset/discontinued. We will use a web scraping approach instead.

We'll implement a solution based on the existing search.php code that:
1. Searches for logos using Google Images or Brave Search as a fallback
2. Extracts image URLs from the search results
3. Returns the results to the frontend for display
4. Allows the user to select a logo to download

This approach has several advantages:
- No dependency on third-party APIs that may be discontinued
- More comprehensive search results
- No API rate limits or costs
- Ability to find logos for a wider range of products

We'll also continue to include the Clearbit Logo API results as a fallback option until it's completely discontinued.

### Frontend Implementation
The frontend changes will include:
1. Adding a search icon that appears when text is entered in the product name field
2. Creating a modal to display search results
3. Implementing JavaScript to handle the search and selection process

### Image Processing
Once a logo is selected:
1. The image will be downloaded to the server
2. It will be processed to ensure it meets the required dimensions (320x120 for logos, 128x128 for favicons)
3. We'll use image manipulation libraries to modify the logo on the fly to create both the main logo and favicon versions
4. The images will be stored in the appropriate directories ("client/1/product/logos" for logos and "client/1/product/favicons" for favicons)
5. The product record will be updated with the new image paths

For image processing, we'll use the Intervention Image library that's already included in Laravel to:
- Resize images to the required dimensions
- Maintain aspect ratios
- Optimize image quality
- Create different versions of the same base logo

## Timeline
- Backend API Development: 3 days
- Frontend Implementation: 2 days
- Integration with Existing Code: 2 days
- Testing and Validation: 2 days
- Documentation and Deployment: 1 day

Total estimated time: 10 days