# sdch-tools
Tools for working with SDCH dictionaries. And some benchmarks.

## Usage

For basic usage, simply run `./complete.sh`. It will automatically perform the following three steps:

1.  Run `./lib/download-html.sh` to download 30 random pages from Wikipedia. 10 for training and 20 for testing.
1.  Run `./build-dictionary.sh` to build a dictionary from the training pages.
1.  Run `./test-sdch.sh` to run VCDiff (SDCH), GZip and SDCH+GZip on all the pages and produce a report of savings.

## Example Report

Below is an example report. The dictionary that was generated on this run was just 58 KB. The rightmost column indicates the savings of using SDCH+GZip compared to just using GZip.

```
                                             Original        VCDiff             GZip              Both        SDCH v.
                                 File Name     Size        Size    Saved     Size    Saved     Size    Saved   GZip  
                   Camden_Park_Estate.html    101.3 KB     45.7 KB 54.9%     25.4 KB 74.9%     15.5 KB 84.7%  (  39%)
                         Carl_Brazley.html    106.9 KB     51.9 KB 51.5%     24.1 KB 77.5%     14.2 KB 86.7%  (41.1%)
               Dennis_A_Murphy_Trophy.html     88.3 KB     33.1 KB 62.5%     20.7 KB 76.6%     10.8 KB 87.8%  (47.9%)
                            Fire_Ball.html     94.5 KB       39 KB 58.7%     22.3 KB 76.4%     12.5 KB 86.8%  (44.1%)
                        Francis_Girod.html     96.5 KB     40.3 KB 58.2%     22.4 KB 76.8%     12.4 KB 87.1%  (44.5%)
                      Gavrail_Panchev.html     90.7 KB     35.2 KB 61.2%       22 KB 75.8%       12 KB 86.7%  (45.2%)
                         Geisonoceras.html     80.4 KB     25.7 KB 68.1%     19.1 KB 76.3%      9.2 KB 88.5%  (51.7%)
                    Jackeline_Olivier.html       89 KB     33.8 KB   62%     20.9 KB 76.5%     10.9 KB 87.7%  (47.6%)
                           Mavis_Tate.html     95.5 KB     39.7 KB 58.5%     22.4 KB 76.6%     12.5 KB 86.9%  (44.3%)
                               Meknes.html    147.3 KB     87.8 KB 40.4%     32.9 KB 77.7%       23 KB 84.4%  (30.1%)
               Mill_Bay_County_Antrim.html    103.1 KB     47.5 KB   54%     23.6 KB 77.1%     13.7 KB 86.7%  (42.1%)
Niagara_Falls_Ontario_railway_station.html    100.3 KB     44.2 KB 55.9%     22.9 KB 77.2%     12.9 KB 87.1%  (43.6%)
           Petaloconchus_interliratus.html     87.6 KB     31.9 KB 63.5%     20.1 KB   77%     10.2 KB 88.4%  (49.4%)
                     Semen_collection.html    152.7 KB     96.9 KB 36.6%     33.7 KB   78%     23.8 KB 84.4%  (29.3%)
                         Stphane_Cali.html       90 KB     34.4 KB 61.8%     20.8 KB 76.9%     10.8 KB   88%  (47.9%)
                               Tahlee.html    166.3 KB    110.7 KB 33.4%     32.8 KB 80.3%       23 KB 86.2%  (  30%)
            The_Philosophical_Lexicon.html     82.9 KB     27.9 KB 66.3%     19.6 KB 76.4%      9.7 KB 88.3%  (50.4%)
                    Trenton_Gas_Field.html    100.1 KB     44.3 KB 55.8%     23.2 KB 76.8%     13.3 KB 86.7%  (42.7%)
                              TriPLet.html     92.3 KB       36 KB   61%     21.5 KB 76.7%     11.6 KB 87.4%  (  46%)
                         Yamaha_Motif.html    115.9 KB     60.1 KB 48.1%     26.5 KB 77.1%     16.6 KB 85.7%  (37.4%)
                                    Totals     2082 KB      966 KB 53.6%      477 KB 77.1%      279 KB 86.6%  (41.6%)
```

## Understanding the Example

The `css-dictionary-extract.php` and `create-final-dictionary.php` scripts are useful scripts for creation of a dictionary from training HTML and CSS files. The various programs in the lib folder download the HTML pages from Wikipedia and prepare them for testing. In particular, "bare" versions of the pages are created with no included styles. The testing pages, meanwhile have their CSS inlined, as per our current best practice for best results with SDCH.

The `css-dictionary-extract.php` uses a very simplified CSS parser to generate a dictionary with long and common values from the CSS. Given the minimal size of the CSS on Wikipedia, it would be possible to just include the entire CSS file in the dictionary, however, on sites with significantly more CSS, or CSS that varies significantly between pages, these smaller CSS dictionaries keep the size small while still providing most of the benefits.

The `create-final-dictionary.php` combines the CSS and bare HTML dictionaries, adds required headers and computes the client and server keys necessary for serving the dictionary. The *bare* dictionary, used for actual encoding will be created as `SERVER_KEY.bare`, while a version with headers will be created as `SERVER_KEY.dct`. Finally, a config file with the client and server keys is created at `dictionary.config`.

## License

This software is provided under an Apache open source license in the hopes that it may be useful. While these exact files are unlikely to work in exactly the right way for other build workflow, they should provide a complete working example to build from.

## Compatibility

These example programs work on recent OS X and CentOS. In theory, all of these scripts and programs can run on windows, although it will be necessary to convert the BASH scripts to bat scripts. We welcome any pull requests for system compatibility, other interesting test metrics, or other methods of creating the dictionary. In particular, handling the javascript as well as the CSS may result in additional savings.
